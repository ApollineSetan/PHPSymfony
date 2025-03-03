<?php

// Alias pour le répertoire src 
namespace App\Controller;

// Imports nécessaires à Response et Route
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController {
    
    // Le typage mixed permet de passer n'importe quel type de valeur quand on ne le connaît pas (mais ça pourrait être int s'il attendait forcément deux entiers)
    #[Route(path: '/accueil/{nbr1}/{nbr2}', name:'app_accueil_addition')]
    public function addition(mixed $nbr1, mixed $nbr2): Response {

        if ($nbr1 < 0 && $nbr2 < 0 ){
            return new Response("<p>Les nombres sont négatifs.</p>");
        }

        $result = $nbr1 + $nbr2; 

        return new Response("<p>L'addition de " . $nbr1 . " et " . $nbr2 . " est égale à : " . $result . "</p>");
    }

    #[Route(path: '/accueil/{nbr1}/{operateur}/{nbr2}', name:'app_accueil_calculatrice')]
    public function calculatrice(int $nbr1, string $operateur, int $nbr2): Response {
        $resultat = "<p>Erreur : opérateur incorrect</p>";

        if ($operateur == "add"){
            $resultat = ("<p>L’addition de " . $nbr1 . " et " . $nbr2 . " est égale au résultat : " . ($nbr1 + $nbr2) . "</p>");
        } elseif ($operateur == "sous") {
            $resultat = ("<p>La soustraction de " . $nbr1 . " et " . $nbr2 . " est égale au résultat : " . ($nbr1 - $nbr2) . "</p>");
        } elseif ($operateur == "multi") {
            $resultat = ("<p>La multiplication de " . $nbr1 . " par " . $nbr2 . " est égale au résultat : " . ($nbr1 * $nbr2) . "</p>");
        } elseif ($operateur == "div") {
            $resultat = ("<p>La division de " . $nbr1 . " par " . $nbr2 . " est égale au résultat : " . ($nbr1 / $nbr2) . "</p>");
        }
        return new Response($resultat);
    }
}