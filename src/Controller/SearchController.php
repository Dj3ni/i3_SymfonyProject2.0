<?php

namespace App\Controller;

use App\Entity\GamingPlace;
use App\Enum\EventType;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use App\Repository\GamingPlaceRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Serializer\EventCustomNameConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends AbstractController
{
    public ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/events/search', name: 'event_search')]
    public function eventsSearch(
        Request $req,
        EventRepository $rep,
        SerializerInterface $serializerInterface): Response
    {
        $form = $this->createForm(SearchFormType::class);
        // dd($form);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            // dd($data);
            $events = $rep->findEventByTitles($data);
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"],
            ]);
            return new Response($eventsJson);
            // return $this->render('search/events_show.html.twig', [
            //     "events" => $events,
            //     "eventsJson" => $eventsJson,
            // ]); ;
        }
        else {
            $events = $rep->findAll();
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"]
            ]);
            // dd($eventsJson);
            
            return $this->render('search/events_search.html.twig', [
                'form' => $form,
                "events" => $events,
                "eventsJson" => $eventsJson,
            ]);
        }
    }

    #[Route("events/calendar", name: "calendar")]
    public function Calendar(EventRepository $rep, SerializerInterface $serializerInterface ):Response
    {
        $events = $rep->findAll();
        $allOccurrences = [];
        foreach ($events as $event){
            foreach ($event->getOccurrences() as $occurrence){
                $allOccurrences[] = [
                    'id' => $event->getId(),
                    'title' => $event->getTitle(),
                    'start' => $occurrence->getDateStart()->format('Y-m-d H:i'),
                    'end' => $occurrence->getDateEnd()->format('Y-m-d H:i'),
                    'eventType' => $event->getEventType()
                ];
            }
        }
            // $eventsJson = $serializerInterface->serialize ($events,"json", [
            //     AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"],
            // ]);
            $occurrencesJson = json_encode($allOccurrences);
            
            // dd($eventsJson);
        return $this->render("search/events_calendar.html.twig", [
                "events" => $events,
                // "eventsJson" => $eventsJson,
                "occurrencesJson" => $occurrencesJson,
        ]);
    }

    #[Route("events/map", name: "events_map")]
    public function eventsMap(GamingPlaceRepository $gpRep, EventRepository $rep, SerializerInterface $serializerInterface ):Response
    {
        $events = $rep->findAll();
        $gamingPlaces = $gpRep->findAll();
            // $eventsJson = $serializerInterface->serialize ($events,"json", [
            //     AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"]
            // ]);
        return $this->render("search/events_map.html.twig", [
                "events" => $events,
                // "eventsJson" => $eventsJson,
                "gamingPlaces"=>$gamingPlaces,
        ]);
    }

    #[Route("/search/{type}", name: "search_type")]
    public function searchByType(EventType $type, EventRepository $rep){
        
        $events = $rep->findByType($type);

        return $this->render("event/events_show.html.twig", [
            "events"=> $events,
        ]);
    }

    #[Route('/gamingplaces/addresses', name:'gamingPlaces_address_list', methods:['GET'])]
    public function addressList(GamingPlaceRepository $rep):JsonResponse
    {
        $gamingPlaces = $rep->findAll();
        // dd($gamingPlaces);
        $addressData = [];
        foreach ($gamingPlaces as $gamingPlace){
            $address = $gamingPlace->getAddress();
            $addressData[] = [
                "name"=>$gamingPlace->getName(),
                "city" =>$address->getCity(),
                "lat" =>$address->getLat(),
                "lon"=>$address->getLon(),
            ];

            // dd($address);
        }
        return new JsonResponse($addressData);
    }

    #[Route('/events/addresses', name:'events_address_list', methods:['GET'])]
    public function EventsAddressList(EventRepository $rep):JsonResponse
    {
        $events = $rep->findAll();
        // dd($gamingPlaces);
        $addressData = [];
        foreach ($events as $event){
            $eventPlaces = $event->getEventPlaces();
            foreach($eventPlaces as $eventPlace){
                $address = $eventPlace->getGamingPlace()->getAddress();
                // dd($address);
                $addressData[] = [
                    "eventTitle" => $event->getTitle(),
                    "name"=>$eventPlace->getGamingPlace()->getName(),
                    "city" =>$address->getCity(),
                    "postCode"=>$address->getPostCode(),
                    "lat" =>$address->getLat(),
                    "lon"=>$address->getLon(),
                ];
            }
        }
        return new JsonResponse($addressData);
    }

}
