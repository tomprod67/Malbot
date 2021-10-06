<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\LoginFormRequestRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/admin-panel/user")
 */
class UserCrudController extends AbstractController{

    /**
     *@Route("/show-user/", name="show-all-user")
     */
    public function showAll(UserRepository $repo, Request $request, PaginatorInterface $paginator){
        $user = $repo->findBy(array(), array('id'=> 'DESC'));

        $pagination = $paginator->paginate(
            $user, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('private/admin/user/show-all.html.twig',
            ['pagination' => $pagination]);
    }

    /**
     *@Route("/show-user/{id}", name="show-one-user")
     */
    public function showOne(UserRepository $repo, Request $request,int $id)
    {
        $user = $repo->findOneBy(['id' => $id]);
        return $this->render('private/admin/user/show-one.html.twig',
            ['user' => $user]);
    }

    /**
     *@Route("/new", name="new-user")
     */
    public function new(Request $request, UserRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $roles = $request->request->get('role');
        $arrayRole = [];
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $request->request->get('password');
                $passwordR = $request->request->get('password-repeat');
                // Si les 2 champ password sont remplis
                if (!empty($password) && !empty($passwordR)) {
                    if ($password === $passwordR){
                        $pwd = $encoder->encodePassword($user,$password);
                        $user->setPassword($pwd);

                        foreach ($roles as $role){
                            if ($role === 'ADMIN') {
                                array_push($arrayRole, 'ROLE_ADMIN');
                            }
                            if ($role === 'USER') {
                                array_push($arrayRole, 'ROLE_USER');
                            }
                        }
                        $user->setRoles($arrayRole);

                        $em->persist($user);
                        $em->flush();
                        $this->addFlash('success', "Votre demande a bien été prise en compte !");
                        return $this->redirectToRoute('show-all-user');

                    }
                }
            }
            $this->addFlash('danger', "Votre demande n'a pas été prise en compte car elle comporte une ou plusieurs erreur(s). Veuillez réessayer.");
            return $this->render('private/admin/user/new.html.twig', [
                'form' => $form->createView(),
                "roles" => $user->getRoles()

            ]);
        }
        return $this->render('private/admin/user/new.html.twig', [
            'form' => $form->createView(),
            "roles" => $user->getRoles()

        ]);
    }

    /**
     *@Route("/update/{id}", name="update-user")
     */
    public function update(int $id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {

                $roles = $request->request->get('role');
                $arrayRole = [];
                foreach ($roles as $role) {
                    if ($role === 'ADMIN') {
                        array_push($arrayRole, 'ROLE_ADMIN');
                    }
                    if ($role === 'USER') {
                        array_push($arrayRole, 'ROLE_USER');
                    }
                }
                if ($request->request->has('null')) {
                    $user->setLastLogin(null);
                }
                $user->setRoles($arrayRole);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre modification a bien été pris en compte.');
            } else {
                $this->addFlash('error', "La modification n'a pas pu être prise en compte");
            }
        }

        return $this->render("private/admin/user/update.html.twig", [
            "form" => $form->createView(),
            "roles" => $user->getRoles(),
            "id" => $user->getId()
        ]);
    }
    /**
     *@Route("/delete/{id}", name="delete-user")
     */
    public function delete(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', "L'objet a bien été suprimer.");

        return $this->redirectToRoute("show-all-user");
    }

    /**
     * @Route("/search", name="search-user")
     */
    public function search(Request $request,UserRepository $repo, PaginatorInterface $paginator)
    {
        /* Si le formulaire de recherche n'a pas été rempli et si la variable session existe (pour la pagination) */
        if ($request->request->has('search') !== true && $request->getSession()->has('user')){
            $userSession = $request->getSession()->get('user');
            $user = $userSession;
            if (is_array($user)) {
                $pagination = $paginator->paginate(
                    $user, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    5 /*limit per page*/
                );
                /* Si plusieurs resulats */
                return $this->render('private/admin/user/search-result.html.twig',
                    ['pagination' => $pagination]);
            }
            /* Si un seul resulat */
            return $this->render('private/admin/user/search-one-result.html.twig',
                ['user' => $user]);

        }
        /* Sinon le formulaire de recherche a été rempli, on recuperes les variable et on effectue les recherches */
        if ($request->request->has('search')){
            $search = $request->request->get('search');
            $categorie = $request->request->get('categorie');
            $user = null;
            switch ($categorie) {
                case "id":
                    $user = $repo->findOneBy(['id' => $search]);
                    break;
                case "username":
                    $user = $repo->findBy(array('username'=>$search), array('id'=> 'DESC'));
                    break;
                default:
                    $user = null;
            }
            /* Si les recherches donne quelque chose */
            if ($user !== null){
                /* On vérifie si il y a une ou plusieurs données à afficher et on envoie sur différentes vues en fonction */
                if (is_array($user)) {
                    $pagination = $paginator->paginate(
                        $user, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        5 /*limit per page*/
                    );
                    /* On stock la variable[tableau d'objets] en session pour la pagination  et ne pas refaire la recherche a chaque fois*/
                    $request->getSession()->set('user', $user);
                    return $this->render('private/admin/user/search-result.html.twig',
                        ['pagination' => $pagination]);
                }
                /* Si il n'y a qu'un seul résultat pas besoin de stocker en session, on rend à la vue */
                return $this->render('private/admin/user/search-one-result.html.twig',
                    ['user' => $user]);
            }
        }
        /* Sinon si aucune des 2 variables n'existe la recherche n'a donné aucun résulats */
        $this->addFlash('error', 'Aucun résultat ne correspond à votre recherche');
        return $this->redirectToRoute("show-all-user");
    }

    /**
     * @Route("/password-change/{id}", name="admin-password-change")
     */
    public function passwordChange(UserPasswordEncoderInterface $encoder, UserRepository $repo, Request $request, $id)
    {
        // Si le form est submit
        if ($request->request->has('valid')){
                $password = $request->request->get('password');
                $passwordR = $request->request->get('password-repeat');
            // Si les 2 champ password sont remplis
            if (!empty($password) && !empty($passwordR)) {
                if ($password === $passwordR){
                    $em = $this->getDoctrine()->getManager();
                    $user = $repo->findOneBy(['id' => $id]);
                    $pwd = $encoder->encodePassword($user,$password);
                    $user->setPassword($pwd);
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Le mot de passe à bien été changer.');
                    return $this->redirectToRoute("show-one-user", ['id' => $id]);
                }
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques.');
                return $this->render("private/admin/user/passwordChange.html.twig", ['id' => $id]);

            }
            $this->addFlash('error', 'Les deux champs doivent être remplis pour pouvoir changer le mot de passe de cet utilisateur.');
            return $this->render("private/admin/user/passwordChange.html.twig", ['id' => $id]);
        }
        // Sinon le form n'est pas submit
        return $this->render("private/admin/user/passwordChange.html.twig", ['id' => $id]);
    }
}