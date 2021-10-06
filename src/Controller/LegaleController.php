<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LegaleController extends AbstractController
{

    /**
     * @Route("/mentions-legale", name="legale-notice")
     */
    public function showLegaleNotice()
    {
        return $this->render('public/legale/legale-notice.html.twig');
    }
    /**
     * @Route("/politique-de-confidentialité", name="privacy-policy")
     */
    public function showPrivacyPolicy()
    {
        return $this->render('public/legale/privacy-policy.html.twig');
    }
}
