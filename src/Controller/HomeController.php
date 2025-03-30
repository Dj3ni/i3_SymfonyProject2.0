<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\EventRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EventRepository $rep): Response
    {
        // $em = $this->doctrine()->getManager();
        $lastEvents = $rep->findLastEvents(3);
        return $this->render('home/index.html.twig',[
            "lastEvents" => $lastEvents,
        ]);
    }

    #[Route("/contact", name:"contact" )]
    public function contact(Request $req, MailerInterface $mailerInterface): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            // dd($form->getErrors());
            $data = $form->getData();
            // dd($data);
            try{
                $email = (new TemplatedEmail())
                        ->from($data["email"])
                        ->to("contact@demo.be")
                        ->subject("seekContact")
                        ->htmlTemplate("emails/contact.html.twig")
                        ->context(["data" => $data]);
                $mailerInterface->send($email);
            }
            catch (TransportExceptionInterface $e){
                $this->addFlash("danger", "Impossible to send your email");
                dump($e->getMessage());
                die();
            }
            
            $this->addFlash("success", "Message received 5/5!");

            return $this->redirectToRoute("contact");
        }

        return $this->render("home/contact.html.twig",[
            "form"=>$form,
        ]);
    }
}
