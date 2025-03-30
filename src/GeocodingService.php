<?php

namespace App;

use App\Entity\Address;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class GeocodingService
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getCoordinatesFromAddress(Address $address): ?array{
        
        $fullAddress = sprintf(
            '%s, %s, %s, %s',
            $address->getStreet(),
            $address->getCity(),
            $address->getPostCode(),
            $address->getCountry()
        );

        $countryParam = substr(strtolower($address->getCountry()),0,2); //I want the 2 first caracters in lower case

        $url = 'https://api.maptiler.com/geocoding/' . urlencode($fullAddress). ".json";
        
        $params = [
            'key' => $this->apiKey,
            'limit' => 1,
            'country'=> $countryParam,
        ];

        $response = $this->client->request('GET', $url, ['query' => $params]);
        $data = $response->toArray();

        if (empty($data['features'])) {
            return null; // Si aucun résultat n'est trouvé
        }

        // Extraction des coordonnées latitude/longitude
        $lat = $data['features'][0]['geometry']['coordinates'][1];
        $lon = $data['features'][0]['geometry']['coordinates'][0];

        return ['latitude' => $lat, 'longitude' => $lon];


    }




















}