<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController {

     /**
      * @Route("/", name="home")
     */
    public function showHome()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/", name="home")
     */
    public function form(Request $request, \Swift_Mailer $mailer){
        return ContactFormController::newContactForm($request, 'home', $this, $mailer);
    }
}
