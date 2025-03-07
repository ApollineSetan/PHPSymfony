<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Password\PasswordHasherInterface;

class ApiAccountController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/api/accounts', name: 'api_account_all', methods: ['GET'])]
    public function getAllAccounts(): Response
    {
        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']
        );
    }

    // Ajouter un compte
    #[Route('/api/account', name: 'api_account_add', methods: ['POST'])]
    public function addAccount(Request $request): Response 
    {
        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');
        if ($account->getFirstname() && $account->getLastname() && $account->getEmail() && $account->getPassword() && $account->getRoles()) {

            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $this->em->persist($account);
                $this->em->flush();
                $code = 201;
            }

            else {
                $account = "Account existe déja";
                $code = 400;
            }
        } else {
            $account = "Veuillez remplir tous les champs";
            $code = 400;
        }

        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => $this->getParameter('allowed_origin'),
        ], ["groups" => "account:create"]);
    }

    // Mettre à jour un compte
    #[Route('/api/account/{email}', name: 'api_account_update', methods: ['PUT'])]
    public function updateAccount(Request $request, string $email): Response
    {
        $account = $this->accountRepository->findOneBy(['email' => $email]);

        if (!$account) {
            return $this->json([
                'error' => 'Account not found'
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['firstname']) && isset($data['lastname'])) {
            $account->setFirstname($data['firstname']);
            $account->setLastname($data['lastname']);

            $this->em->flush();

            return $this->json([
                'message' => 'Account updated successfully',
                'account' => [
                    'id' => $account->getId(),
                    'firstname' => $account->getFirstname(),
                    'lastname' => $account->getLastname(),
                    'email' => $account->getEmail(),
                ]
            ], 200);
        }

        return $this->json([
            'error' => 'Invalid data provided'
        ], 400);
    }

    // Supprimer un compte
    #[Route('/api/account/{id}', name: 'api_account_delete', methods: ['DELETE'])]
    public function deleteAccount(int $id): Response
    {
        $account = $this->accountRepository->find($id);
        if (!$account) {
            return $this->json([
                'error' => 'Account not found'
            ], 404);
        }

        $this->em->remove($account);
        $this->em->flush();

        return $this->json([
            'message' => 'Account successfully deleted'
        ], 200);
    }

    // Mettre à jour le mot de passe
    #[Route('/api/account/{email}/password', name: 'api_account_update_password', methods: ['PATCH'])]
    public function updatePassword(Request $request, string $email, PasswordHasherInterface $passwordHasher): Response
    {
        $account = $this->accountRepository->findOneBy(['email' => $email]);

        if (!$account) {
            return $this->json(
                ['error' => 'Account not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['password']) || empty($data['password'])) {
            return $this->json(
                ['error' => 'Password is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $hashedPassword = $passwordHasher->hashPassword($account, $data['password']);

        $account->setPassword($hashedPassword);

        $this->em->flush();

        return $this->json(
            ['message' => 'Password successfully updated'],
            Response::HTTP_OK
        );
    }

}
