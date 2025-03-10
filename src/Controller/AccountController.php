<?php 

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController {

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/account', name: 'app_accountcontroller_showallaccount')]
    public function showAllAccount(): Response
    {
        $accounts = $this->accountRepository->findAll();
        return $this->render('account.html.twig', ['accounts' => $accounts]);
    }

    #[Route('/account/add', name: 'app_accountcontroller_add')]
    public function addAccount(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        $msg = "";
        $status = "";

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($account);
                $this->em->flush();
                $msg = 'Le compte a été ajouté avec succès';
                $status = 'success';
            } catch (\Exception $e) {
                $msg = 'Une erreur est survenue lors de l\'ajout du compte';
                $status = 'danger';
            }
        }

        $this->addFlash($status, $msg);

        return $this->render('addaccount.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
