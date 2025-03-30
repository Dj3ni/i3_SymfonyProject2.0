<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\EventSubscription;
use App\EventOccurrenceGenerator;
use App\Repository\EventRepository;
use App\Form\EventSubscriptionFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EventOccurrenceSubscription;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventSubscriptionRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\EventOccurrenceSubscriptionRepository;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionsController extends AbstractController
{
    public ManagerRegistry $doctrine;
    private EventOccurrenceGenerator $occurrenceGenerator;

    public function __construct(ManagerRegistry $doctrine, EventOccurrenceGenerator $occurrenceGenerator){
        $this->doctrine = $doctrine;
        $this->occurrenceGenerator = $occurrenceGenerator;
    }

    #[Route('/event_subscription/{id}', name: 'event_subscription')]
    public function eventSubscription(
        Event $event,
        Request $request,
        EventOccurrenceSubscriptionRepository $eventOccurrenceSubscriptionRepository,
        ): Response
    {   
        // 1. Get user
        $user = $this->getUser();
        // dd($user);

        // 2. Get all occurrences and set data
        $occurrences = $event->getOccurrences();

        $eventOccurrenceSubscriptionFormData = [];
        $em = $this->doctrine->getManager();
        
        // Create a new event subscription (no form!) for each occurrence
        foreach ($occurrences as $occurrence){
            // 2.1. Search if already subscripted otherwise don't show
            $eventOccurrenceSubscription = $eventOccurrenceSubscriptionRepository->findOneBy(['user' => $user,
            'eventOccurrence' => $occurrence]);
            
            //2. 2. If user not already subscribed, create new subscription anset details
            if(!$eventOccurrenceSubscription){
                $eventOccurrenceSubscription = new EventOccurrenceSubscription();
                $eventOccurrenceSubscription    -> setEventOccurrence($occurrence)
                                                -> setUser($user)
                                                -> setNumberParticipants(1);
            }

            // 2. 3. Add it to form data
            $eventOccurrenceSubscriptionFormData[] = $eventOccurrenceSubscription;
        }

        // 3. Create Form
        $form = $this->createForm(EventSubscriptionFormType::class, [
            'eventOccurrenceSubscriptions' => $eventOccurrenceSubscriptionFormData,
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Update fields for occurrence subscription
            $eventOccurrenceSubscriptions = $form ->getData()['eventOccurrenceSubscriptions'];

            // dump ($request->get("event_subscription_form")['eventOccurrenceSubscriptions'][$key]);
            foreach($eventOccurrenceSubscriptions as $key => $eventOccurrenceSubscription){
                $subscribe = false; // at start, none checked
                
                // Check if subscribe checkbox checked
                if (isset($request->get("event_subscription_form")['eventOccurrenceSubscriptions'][$key]["subscribe"])){
                    $subscribe = true;
                }
                // if unchecked and user already subscripted, delete subscription
                // else{
                //     $em->remove($eventOccurrenceSubscription);
                // }

                // if checked, persist()
                if($subscribe){
                    // set subscriptionDate
                    $eventOccurrenceSubscription->setSubscriptionDate(new \DateTimeImmutable());
                    $em -> persist($eventOccurrenceSubscription);
                    
                }
                $em->flush();
                // dd("les occurrencesSubscriptions sont bien associées à l'User et a l'EventOccurrence");
            }
             // message to inform the user
            $this->addFlash("success", "Subscription sent!");
            return $this->redirectToRoute("event_search");
        }
        
        return $this->render('event/event_subscription.html.twig',[
            "form"=>$form,
            "event"=>$event,
        ]);
    }

}
