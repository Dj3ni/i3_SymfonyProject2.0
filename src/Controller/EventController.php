<?php

namespace App\Controller;

use App\Demo;
use App\Entity\Event;
use App\Entity\Address;
use App\GeocodingService;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
use App\AddNewGamingPlaceService;
use App\EventOccurrenceGenerator;
use App\Form\CreateEventFormType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    
    private ManagerRegistry $doctrine;
    private GeocodingService $geocodingService;
    private AddNewGamingPlaceService $addNewGamingPlaceService;


    public function __construct(
        ManagerRegistry $doctrine,
        EventOccurrenceGenerator $occurrenceGenerator, 
        GeocodingService $geocodingService,
        AddNewGamingPlaceService $addNewGamingPlaceService)
    {
        $this->doctrine = $doctrine;
        $this->geocodingService = $geocodingService;
        // $this->occurrenceGenerator = $occurrenceGenerator;
        $this->addNewGamingPlaceService = $addNewGamingPlaceService;
    }

############  Show all the events in DB now managed by events/search
    
    #[Route('/events', name: 'events_show')]
    public function showAllEvents(EventRepository $rep): Response
    {
        $events = $rep ->findAll();
        // dd($events);
        
        return $this->render('event/events_show.html.twig', [
            'events' => $events,
        ]);
    }
    
############# Show the Event Informations
    
    #[Route('/event/{id}', name: 'event')]
    public function showEvent(Event $event): Response
    {
        // dd($event);

        // Init occurrences
        $occurrences = $event->getOccurrences();
        // dd($occurrences);
        $places = $event->getEventPlaces();
    
        // dd($places[0]->getGamingPlace()->getAddress());

        return $this->render('event/event_info.html.twig', [
            'event' => $event,
            "occurrences"=> $occurrences,
            "places"=>$places,
        ]);
    }
    
############# Creation Event Form

    #[Route('/create_event', name: 'create_event')]
    #[IsGranted('ROLE_ADMIN')]
    public function createEvent(Request $request, EventOccurrenceGenerator $occurrenceGenerator): Response
    {
        $em = $this->doctrine->getManager();

        // 1. Create new empty object
        $event = new Event();
        // $eventPlace = new EventPlace();
        // $event->addEventPlace($eventPlace);
        // $em->persist($eventPlace);

        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        // 3.Send in DB
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUserOrganisator($this->getUser());
            $em->persist($event);

            // Create Occurrences
            $occurrenceGenerator->generateOccurrences($event);
            $em->flush();
            // dd($event);
            $this->addFlash("success", "Event successfully created!");
            return $this->redirectToRoute("event", ["id" => $event->getId()]);
        }
        return $this->render('event/event_create_form.html.twig', [
            'form' => $form,
        ]);
    }


############### Update Event Form

    #[Route('/update_event/{id}', name: 'update_event')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateEvent(Event $event, EventOccurrenceGenerator $occurrenceGenerator, Request $request): Response
    {
        $em = $this->doctrine->getManager();
        
        // 1. Stock old recurrence to check if change
        $oldReccurrence = $event->getRecurrenceType();
        
        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            //  Check if recurrence change
            if ($event->getRecurrenceType() !== $oldReccurrence){
                // Remove old occurrences
                foreach($event->getOccurrences() as $occurrence){
                    $event->removeOccurrence($occurrence);
                }
                // create new occurences
                $occurrenceGenerator->generateOccurrences($event);
            }
            // 3.Send in DB
            $em->flush();
            
            $this->addFlash("success", "Your event is now up-to-date");
            return $this->redirectToRoute("event", ["id"=> $event->getId()]);
        }
        
        return $this->render('event/event_update_form.html.twig', [
            'form' => $form,
            "event"=> $event,
        ]);
    }
    
################## Delete Event

    #[Route('/delete_event/{id}', name: 'delete_event')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteEvent(Event $event, Request $request): Response
    {
        // We want to protect deletion by asking if sure:
            if ($request->isMethod("POST")){
                $action = $request->request->get("action"); //listens to the action type
                $em = $this->doctrine->getManager();

                if ($action === "delete_event"){
                    // Delete event and occurrences
    
                    // 2. Get linked entities and remove them        
                    $eventPlaces = $event->getEventPlaces();
                    // dd($eventPlaces);                
                    foreach($eventPlaces as $eventPlace){
                        $em->remove($eventPlace);
                    }
                    
                    // 3. Remove Event
                    $em->remove($event);
                }   
                else if ($action === "delete_occurrences"){
                    // Delete only occurrences
                    foreach ($event->getOccurrences() as $occurrence){
                        $em->remove($occurrence);
                    }
                    return $this->redirectToRoute("event",[
                        "id"=> $event->getId(),
                    ]);
                }

                // 4.Sync in DB
                $em->flush();
                
                // 5. Redirect with message
                $this->addFlash("success", "Your event was successfully removed!");
                return $this->redirectToRoute("event_search");
            }

        return $this->render("event/confirm_delete.html.twig", [
            "event"=> $event,
        ]);
    }

    ######################### Api Event Address

    #[Route('/event/{id}/addresses', name:'event_address_list', methods:['GET'])]
    public function EventAddressList(Event $event):JsonResponse
    {
        // dd($event);
        $addressData = [];
        $eventPlaces = $event->getEventPlaces();
            foreach($eventPlaces as $eventPlace){
                $address = $eventPlace->getGamingPlace()->getAddress();
                $addressData[] = [
                    "city" =>$address->getCity(),
                    "lat" =>$address->getLat(),
                    "lon"=>$address->getLon(),
                    "eventTitle"=>$event->getTitle(),
                    "postCode"=>$address->getPostCode(),
                    "name"=>$eventPlace->getGamingPlace()->getName(),

                ];

                // dd($addressData);
            }
            
        return new JsonResponse($addressData);
    }
            
}