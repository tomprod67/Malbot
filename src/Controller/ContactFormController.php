<?php
namespace App\Controller;

use App\Form\Type\ContactType;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ContactForm;
use Symfony\Component\HttpFoundation\Request;


class ContactFormController extends AbstractController{
    public static function newContactForm(Request $request, $route, $instance, \Swift_Mailer $mailer)
    {
        $contactRequest = new ContactForm();
        $form = $instance->createForm(ContactType::class, $contactRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $reCaptcha = new ReCaptcha('6LfTqzsaAAAAALTDBl5IJXTVAf4DKf2fIPJZZSXj');
            $captcha = $reCaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
            if ($captcha->isSuccess() === true){
                if ($form->isValid()) {
                    $dataSubmited = $form->getData();
                    dd($dataSubmited);
                    $name = $dataSubmited->getName();
                    $mail = $dataSubmited->getMail();
                    $adress = $dataSubmited->getAdress();
                    $city = $dataSubmited->getCity();
                    $cp = $dataSubmited->getCp();
                    $telNumber = $dataSubmited->getTelNumber();
                    $message = $dataSubmited->getMessage();
                    $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

                    $contactRequest->setName($name);
                    $contactRequest->setMail(htmlspecialchars($dataSubmited->getMail()));
                    $contactRequest->setMessage(htmlspecialchars($dataSubmited->getMessage()));
                    if ($adress !== null) {
                        $contactRequest->setAdress($adress);
                    }
                    if ($city !== null) {
                        $contactRequest->setCity($city);
                    }
                    if ($cp !== null) {
                        $contactRequest->setCp($cp);
                    }
                    if ($telNumber !== null) {
                        $contactRequest->setTelnumber($telNumber);
                    }


                    $contactRequest->setDate($date);

                    $arraySubmited = [
                        'name' => $name,
                        'mail' => $mail,
                        'adress' => $adress,
                        'city' => $city,
                        'cp' => $cp,
                        'telNumber' => $telNumber,
                        'message' => $message,
                        'date' => $date
                    ];

                    $entityManager = $instance->getDoctrine()->getManager();
                    $entityManager->persist($contactRequest);
                    $entityManager->flush();
                    /*$message = (new \Swift_Message('Formulaire de contact : Nouvelle demande'))
                        ->setFrom("creatsweb@creatsweb.com")
                        ->setTo('contact@creatsweb.com')
                        ->setBody(
                            $instance->renderView(
                                'public/mail/mailFormContact.html.twig', array(
                                'arraySubmit' => $arraySubmited)),
                            'text/html'
                        );

                    $mailer->send($message);*/

                    $instance->addFlash('success', "Votre demande a bien été prise en compte !");
                    return $instance->redirectToRoute($route);
                }
                $instance->addFlash('danger', "Votre demande n'a pas été prise en compte car elle comporte une ou plusieurs erreur(s). Veuillez réessayer.");
                return $instance->render("public/".$route.".html.twig", array(
                    'form' => $form->createView(),
                    'tempValidationForm' => true
                ));
            }
            $instance->addFlash('danger', "Erreur dans la vérication du Captcha. Veuillez réessayer.");
            return $instance->render("public/".$route.".html.twig", array(
                'form' => $form->createView(),
                'tempValidationForm' => true
            ));
        }

        return $instance->render("public/".$route.".html.twig", array(
            'form' => $form->createView()
        ));
    }
}