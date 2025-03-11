<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;

class AccountService
{

    public function __construct(
        private readonly ORMEntityManagerInterface $em,
        private readonly AccountRepository $accountRepository
    ) {}

    public function save(Account $account)
    {
        // Tester si les champs sont tous remplis
        if ($account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" && $account->getPassword() != "") {
            // Tester si le compte n'existe pas
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                // Setter les paramètres
                $account->setRoles("ROLE_USER");
                $this->em->persist($account);
                $this->em->flush();
            } else {
                throw new \Exception("Le compte existe déjà.", 400);
            }
            // Sinon les champs ne sont pas remplis
        } else {
            throw new \Exception("Les champs ne sont pas tous remplis.", 400);
        }
    }

    public function getAll(): array
    {
        $accounts = $this->accountRepository->findAll();

        if (empty($accounts)) {
            throw new \Exception("Aucun compte n'a été trouvé");
        } else {
            return $accounts;
        }
    }

    public function getById(int $id): Account
    {
        $account = $this->accountRepository->find($id);

        if (!$account) {
            throw new \Exception("Le compte avec l'id $id n'existe pas.");
        }

        return $account;
    }
}
