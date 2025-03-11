<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalculController extends AbstractController
{

    #[Route(path: '/calcul/{nbr1}/{operateur}/{nbr2}', name: 'app_calcul_calculatrice')]
    public function calculatrice(mixed $nbr1, string $operateur, mixed $nbr2): Response
    {

        if (!is_numeric($nbr1) || !is_numeric($nbr2)) {
            $resultat = "Veuillez entrer des nombres numériques.";
        } else {
            switch ($operateur) {
                case "add":
                    $resultat = ($nbr1 + $nbr2);
                    break;

                case "sous":
                    $resultat = ($nbr1 - $nbr2);
                    break;

                case "multi":
                    $resultat = ($nbr1 * $nbr2);
                    break;

                case "div":
                    if ($nbr1 == 0 || $nbr2 == 0) {
                        $resultat = "Erreur : Division par zéro impossible.";
                    } else {
                        $resultat = ($nbr1 / $nbr2);
                    }
                    break;

                default:
                    $resultat = "Erreur : opérateur incorrect.";
                    break;
            }
        }

        // Utilisation de Twig pour afficher les variables dans le template Twig
        return $this->render('calculatrice.html.twig', [
            'nbr1' => $nbr1,
            'nbr2' => $nbr2,
            'operateur' => $operateur,
            'resultat' => $resultat,
        ]);
    }
}
