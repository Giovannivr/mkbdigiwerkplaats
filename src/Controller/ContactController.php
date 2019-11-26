<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index()
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    /**
     * @Route("/contact2", name="contact2", methods={"GET","POST"})
     */
    public function index2(Request $request, \Swift_Mailer $mailer): Response
    {

        $form = $this->createFormBuilder()
            ->add('naam', TextType::class)
            ->add('email', EmailType::class)
            ->add('omschrijving', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // haal data uit het form op
            $contactinfo = $form->getData();
          #  dump($contactinfo);
          #  exit;
           $input_naam = $contactinfo['naam'];
           $input_email = $contactinfo['email'];
           $input_omschrijving = $contactinfo['omschrijving'];


            $message = (new \Swift_Message('Dit is 1e mail'))
                ->setFrom($input_email)
                ->setTo('giovannivr@live.com')
                ->setBody(
                    $this->renderView(
                    // templates/contact/email.txt.twig
                        'contact/email.txt.twig',
                        ['variabele1' => $input_naam, 'variabele2' => $input_omschrijving]
                    )
                )
            ;
            # dump($message);
            $mailer->send($message);

            return $this->redirectToRoute('home');


        }

        return $this->render('contact/index2.html.twig', [
            'form' => $form->createView(),
        ]);


    }

}
