<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SanitaireController extends AbstractController
{
    /**
     * @Route("/sanitaire", name="sanitaire")
     */
    public function showHome()
    {
        return $this->render('public/chauffage.html.twig');
    }

    /**
     * @Route("/sanitaire", name="sanitaire")
     */
    public function form(Request $request, \Swift_Mailer $mailer)
    {
        return ContactFormController::newContactForm($request, 'sanitaire', $this, $mailer);
    }
}