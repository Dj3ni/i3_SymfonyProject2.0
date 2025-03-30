<?php

namespace App;

use App\Entity\Address;
use App\GeocodingService;
use App\Entity\GamingPlace;


class AddNewGamingPlaceService {

    private GeocodingService $geocodingService;
    
    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function addNewGamingPlace($newGamingPlace){
        
            $gamingPlace = new GamingPlace([
                "name"=> $newGamingPlace->getName(),
                "type"=>$newGamingPlace->getType(),
                "description"=>$newGamingPlace->getDescription(),
                "placeMax" => $newGamingPlace->getPlaceMax()
            ]);

            $addressForm = $newGamingPlace->getAddress();

            $address = new Address([
                "locality"=> $addressForm->getLocality(),
                "street"=>$addressForm->getStreet(),
                "number"=>$addressForm->getNumber(),
                "city"=>$addressForm->getCity(),
                "postCode"=>$addressForm->getPostCode(),
                "country"=>$addressForm->getCountry(),
            ]);

            $coords = $this->geocodingService->getCoordinatesFromAddress($address);

            if($coords){
                $address->setLat($coords['latitude']);
                $address->setLon($coords['longitude']);
            }
            else{
                $address->setLat(null);
                $address->setLon(null);
            }
            $gamingPlace->setAddress($address);
            
        return $gamingPlace;
    }

}
