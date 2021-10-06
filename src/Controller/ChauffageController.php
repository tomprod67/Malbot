<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ChauffageController extends AbstractController
{
    /**
     * @Route("/chauffage", name="chauffage")
     */
    public function showHome()
    {
        return $this->render('public/chauffage.html.twig');
    }

    /**
     * @Route("/chauffage", name="chauffage")
     */
    public function form(Request $request, \Swift_Mailer $mailer)
    {
        return ContactFormController::newContactForm($request, 'chauffage', $this, $mailer);
    }
}