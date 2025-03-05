<?php 

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController {

    #[Route('/account', name: 'app_accountcontroller_showallaccount')]
    public function showAllAccount(AccountRepository $accountRepository): Response {
        $accounts = $accountRepository->findAll();
        return $this->render('account.html.twig', ['accounts' => $accounts]);
    }
}