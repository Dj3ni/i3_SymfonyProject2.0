<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventOccurrence;
use App\Form\EventOccurrenceFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventOccurrenceController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }

    ######################### Create Occurrence

    #[Route('/create_event_occurrence/{event}', name:'create_occurrence')]
    public function createOccurrence(Event $event, Request $request)
    {
        $occurrence = new EventOccurrence();
        $occurrence->setEvent($event);

        $form = $this->createForm(EventOccurrenceFormType::class, $occurrence);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $occurrence ->setDateStart($form->get("dateStart")->getData())
                        ->setDateEnd($form->get("dateEnd")->getData());
            $em = $this->doctrine->getManager();
            $em->persist($occurrence);
            $em->flush();

            $this->addFlash("success", "Your occurrence was succesfully created");
            return $this->redirectToRoute("event", ["id"=>$event->getId()]);
        }

        return $this->render('event_occurrence/event_occurrence_create.html.twig',[
            "form"=> $form,
            "event"=>$event,
        ]);
    }
    
    ########################## Delete Occurrence

    #[Route('/delete_event_occurrence/{id}' , name: "delete_occurrence")]
    public function deleteOccurrence(EventOccurrence $occurrence): Response
    {
        // dd($occurrence->getEvent()->getId());
        // Check if occurrence exists
        if($occurrence){
            $em = $this->doctrine->getManager();
            $em->remove($occurrence);
            $em->flush();
        }
        $this->addFlash("success", "Your occurrence was successfully removed!");
        return $this->redirectToRoute("event", [
            "id" => $occurrence->getEvent()->getId(),
        ]);
    }

    ################### Update Occurrence

    #[Route('/update_event_occurrence/{id}' , name: "update_occurrence")]
    public function updateOccurrence(EventOccurrence $occurrence, Request $request): Response
    {
        $form = $this->createForm(EventOccurrenceFormType::class, $occurrence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $occurrence->setDateStart($form->get("dateStart")->getData())
                        ->setDateEnd($form->get("dateEnd")->getData());
            
            $em = $this->doctrine->getManager();
            $em->flush();

            $this->addFlash("success", "Your occurrence was successfully updated!");
            return $this->redirectToRoute("event", [
                "id" => $occurrence->getEvent()->getId(),
            ]);
        }
            
        return $this->render("event_occurrence/event_occurrence_update.html.twig", [
            "id" => $occurrence->getEvent()->getId(),
            "form"=>$form,
            "event"=> $occurrence->getEvent(),
            "occurrence"=>$occurrence,
        ]);
    }

    
    // #[Route("occurrence/{id}", name:"occurrence_show")]
    // public function occurrenceShow(EventOccurrence $occurrence):Response
    // {
    //     return $this->render("event/event_info.html.twig", [
    //         "occurrence" => $occurrence,
    //     ]);
    // }

}
