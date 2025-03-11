<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccountService $accountService
    ) {}

    #[Route('/account', name: 'app_accountcontroller_showallaccount')]
    public function showAllAccount(): Response
    {
        $msg = "";
        $status = "";

        try {
            $accounts = $this->accountService->getAll();
        } catch (\Exception $e) {
            $status = "Danger";
            $msg = $e->getMessage();
        }
        $this->addFlash($status, $msg);
        return $this->render('account.html.twig', [
            'accounts' => $accounts
        ]);
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
                // dd($account);
                // Appel de la méthode save d'AccountService
                $this->accountService->save($account);
                $status = "Success";
                $msg = "Le compte a été ajouté en BDD";
                // Capturer les exceptions
            } catch (\Exception $e) {
                $status = "Danger";
                $msg = $e->getMessage();
            }
            $this->addFlash($status, $msg);
        }

        return $this->render('addaccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/{id}', name: 'app_accountcontroller_showaccount', requirements: ['id' => '\d+'])]
    public function showAccountDetail(int $id): Response
    {
        try {
            $account = $this->accountService->getbyId($id);
        } catch (\Exception $e) {
            $erreur = $e->getMessage();
        }

        return $this->render('account_detail.html.twig', [
            'account' => $account ?? null,
            'erreur' => $erreur ?? null,
        ]);
    }
}
