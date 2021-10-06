<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RealisationController extends AbstractController
{
    /**
     * @Route("/réalisations", name="realisation")
     */
    public function showHome()
    {
        return $this->render('public/realisation.html.twig');
    }

    /**
     * @Route("/réalisations", name="realisation")
     */
    public function form(Request $request, \Swift_Mailer $mailer)
    {
        return ContactFormController::newContactForm($request, 'realisation', $this, $mailer);
    }
}