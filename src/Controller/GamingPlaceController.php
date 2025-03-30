<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Address;
use App\GeocodingService;
use App\AddNewGamingPlace;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
use App\Form\EventPlaceFormType;
use App\AddNewGamingPlaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GamingPlaceController extends AbstractController
{
    private EntityManagerInterface $em;
    private GeocodingService $geocodingService;
    private AddNewGamingPlaceService $addNewGamingPlaceService;

    public function __construct(
        EntityManagerInterface $em, 
        GeocodingService $geocodingService,
        AddNewGamingPlaceService $addNewGamingPlaceService)
    {
        $this->em = $em;
        $this->geocodingService = $geocodingService;
        $this->addNewGamingPlaceService = $addNewGamingPlaceService;
    }

    #[Route('/gaming/place', name: 'app_gaming_place')]
    public function index(): Response
    {
        return $this->render('gaming_place/index.html.twig', [
            'controller_name' => 'GamingPlaceController',
        ]);
    }

########################### Remove Event Place ####################

    #[Route('/remove/{eventPlace}/{event}', name:"remove_gaming_place_event")]
    #[IsGranted('ROLE_ADMIN')]
    public function removeGamingPlaceEvent(EventPlace $eventPlace, Event $event){
        
        $event->removeEventPlace($eventPlace);
        $this->em->flush();

        $this->addFlash("success", "You've successfully removed this gamingPlace");
        return $this->redirectToRoute("event", ["id"=>$event->getId()]);
    }

############################ New Gaming-Place + link ########################

    #[Route('add/gamingPlace/{event}', name:"add_gamingPlace_event")]
    #[IsGranted('ROLE_ADMIN')]
    public function addGaminPlaceEvent(Event $event, Request $req)
    {
        $eventPlace = new EventPlace();
        $eventPlace->setEvent($event);

        $form = $this->createForm(EventPlaceFormType ::class, $eventPlace);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            // dd($form);
            // Check if chosen in list or new 
            $chosenGamingPlace = $form->get("gamingPlace")->getData();
            $newGamingPlace = $form->get("newGamingPlace")->getData();
            // dd($chosenGamingPlace,  $newGamingPlace);

            if(!$chosenGamingPlace && $newGamingPlace){
                $gamingPlace = $this->addNewGamingPlaceService->addNewGamingPlace($newGamingPlace);
                $eventPlace->setGamingPlace($gamingPlace);
            }
            else if($chosenGamingPlace){
                $eventPlace->setGamingPlace($chosenGamingPlace);
            }
            else{
                $this->addFlash("error", "There was an error, your place hasn't been added");            
            return $this->redirectToRoute("event",["id"=>$event->getId()]);
            }

            $this->em->persist($eventPlace);
            $this->em->flush();
            $this->addFlash("success", "Your event has a new gaming Place!");            
            return $this->redirectToRoute("event",["id"=>$event->getId()]);
        }

        return $this->render("gaming_place/add_place_event.html.twig", [
            "eventPlace"=>$eventPlace,
            "form"=>$form,
        ]);

    }

}
