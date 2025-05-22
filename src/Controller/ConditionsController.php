<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    #[Route('/cgv', name: 'conditions_generales_vente')]
    public function conditionsGeneralesVente(): Response
    {
        return $this->render('conditions/cgv.html.twig');
    }

    #[Route('/cgu', name: 'conditions_generales_utilisation')]
    public function conditionsGeneralesUtilisation(): Response
    {
        return $this->render('conditions/cgu.html.twig');
    }

    #[Route('/mentions', name: 'mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('conditions/mentions.html.twig');
    }

    #[Route('/confidentialite', name: 'politique_confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('conditions/confidentialite.html.twig');
    }

    #[Route('/cookies', name: 'parametres_cookies')]
    public function parametresCookies(): Response
    {
        return $this->render('conditions/cookies.html.twig');
    }

    #[Route('/billetterie', name: 'cookies_billetterie')]
    public function parametresCookiesBilletterie(): Response
    {
        return $this->render('conditions/billetterie.html.twig');
    }

    #[Route('/cybersecurite', name: 'cybersecurite')]
    public function cyberscurite(): Response
    {
        return $this->render('conditions/cybersecurite.html.twig');
    }

    #[Route('/aide', name: 'centre_aide')]
    public function centreAide(): Response
    {
        return $this->render('conditions/aide.html.twig');
    }
}