<?php


namespace App\Controller\Admin;

use App\Entity\ContactForm;
use App\Form\Type\ContactType;
use App\Repository\ContactRequestRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/admin-panel/contactRequest")
 */
class ContactRequestCrudController extends AbstractController{

    /**
     *@Route("/show-request/", name="show-all-request-contact")
     */
    public function showAll(ContactRequestRepository $repo, Request $request, PaginatorInterface $paginator){
        $contactRequest = $repo->findBy(array(), array('id'=> 'DESC'));

        $pagination = $paginator->paginate(
            $contactRequest, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('security/admin/contactRequest/show-all.html.twig',
            ['pagination' => $pagination]);
    }

    /**
     *@Route("/show-request/{id}", name="show-one-request-contact")
     */
    public function showOne(ContactRequestRepository $repo, Request $request,int $id)
    {
        $contactRequest = $repo->findOneBy(['id' => $id]);
        return $this->render('security/admin/contactRequest/show-one.html.twig',
            ['contactRequest' => $contactRequest]);
    }

    /**
     *@Route("/delete/{id}", name="delete-request-contact")
     */
    public function delete(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $contactRequest = $entityManager->getRepository(ContactForm::class)->find($id);
        $entityManager->remove($contactRequest);
        $entityManager->flush();
        $this->addFlash('success', "L'objet a bien été suprimer.");

        return $this->redirectToRoute("show-all-request-contact");
    }

    /**
     * @Route("/search", name="search-request-contact")
     */
    public function search(Request $request,ContactRequestRepository $repo, PaginatorInterface $paginator)
    {
        /* Si le formulaire de recherche n'a pas été rempli et si la variable session existe (pour la pagination) */
        if ($request->request->has('search') !== true && $request->getSession()->has('contactRequest')){
            $contactRequestSession = $request->getSession()->get('contactRequest');
            $contactRequest = $contactRequestSession;
            if (is_array($contactRequest)) {
                $pagination = $paginator->paginate(
                    $contactRequest, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    5 /*limit per page*/
                );
                /* Si plusieurs resulats */
                return $this->render('security/admin/contactRequest/search-result.html.twig',
                    ['pagination' => $pagination]);
            }
            /* Si un seul resulat */
            return $this->render('security/admin/contactRequest/search-one-result.html.twig',
                ['loginRequest' => $contactRequest]);

        }
        /* Sinon le formulaire de recherche a été rempli, on recuperes les variable et on effectue les recherches */
        if ($request->request->has('search')){
            $search = $request->request->get('search');
            $categorie = $request->request->get('categorie');
            $contactRequest = null;
            switch ($categorie) {
                case "id":
                    $contactRequest = $repo->findOneBy(['id' => $search]);
                    break;
                case "name":
                    $contactRequest = $repo->findBy(array('name'=>$search), array('id'=> 'DESC'));
                    break;
                case "firstname":
                    $contactRequest = $repo->findBy(array('firstname' => $search), array('id' => 'DESC'));
                    break;
                case "mail":
                    $contactRequest = $repo->findBy(array('mail'=>$search));
                    break;
                case "society":
                    $contactRequest = $repo->findBy(array('society' => $search), array('id' => 'DESC'));
                    break;
                case "telnumber":
                    $contactRequest = $repo->findBy(array('telnumber'=>$search));
                    break;
                case "website":
                    $contactRequest = $repo->findBy(array('website'=>$search));
                    break;
                case "message":
                    $contactRequest = $repo->findBy(array('message' => $search));
                    break;
                default:
                    $contactRequest = null;
            }
            /* Si les recherches donne quelque chose */
            if ($contactRequest !== null){
                /* On vérifie si il y a une ou plusieurs données à afficher et on envoie sur différentes vues en fonction */
                if (is_array($contactRequest)) {
                    $pagination = $paginator->paginate(
                        $contactRequest, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        5 /*limit per page*/
                    );
                    /* On stock la variable[tableau d'objets] en session pour la pagination  et ne pas refaire la recherche a chaque fois*/
                    $request->getSession()->set('loginRequest', $contactRequest);
                    return $this->render('security/admin/contactRequest/search-result.html.twig',
                        ['pagination' => $pagination]);
                }
                /* Si il n'y a qu'un seul résultat pas besoin de stocker en session, on rend à la vue */
                return $this->render('security/admin/contactRequest/search-one-result.html.twig',
                    ['contactRequest' => $contactRequest]);
            }
        }
        /* Sinon si aucune des 2 variables n'existe la recherche n'a donné aucun résulats */
        $this->addFlash('error', 'Aucun résultat ne correspond à votre recherche');
        return $this->redirectToRoute("show-all-request-contact");
    }
}