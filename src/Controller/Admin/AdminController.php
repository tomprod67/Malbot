<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController{
    /**
     * @Route("/admin-panel", name="admin-panel")
     */
    public function showHome()
    {
        return $this->render('private/admin/admin-panel.html.twig');
    }
}