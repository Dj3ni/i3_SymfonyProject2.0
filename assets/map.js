import "./styles/map.css";
// Leaflet script + css
import "leaflet";
// import "leaflet/dist/leaflet.css"; /if no dist link
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import "leaflet.markercluster";
// import "leaflet-control-geocoder/dist/Control.Geocoder.css";
// import "leaflet-control-geocoder/dist/Control.Geocoder.js";

const EventsMap = document.getElementById("allEventsMap");
// console.log(EventsMap);
let GamingPlaceMap = document.getElementById("gamingPlacesMap");
const eventInfoMap = document.getElementById("eventMap");
// console.log(eventInfoMap);
// let eventId = eventInfoMap.dataset.event ;

if (EventsMap) {
    initializeMap(EventsMap, "/events/addresses");
} else {
    console.error("EventsMap container introuvable !");
}
if (eventInfoMap) {
    let eventId = eventInfoMap.dataset.event;
    // console.log(eventId);
    initializeMap(eventInfoMap, `/event/${eventId}/addresses`);
} else {
    console.error("eventInfoMap container introuvable !");
}

initializeMap(GamingPlaceMap, "/gamingplaces/addresses");

function initializeMap(mapContainerId, apiEndPoint, intialZoom = 20){
    
    let limits = [];
    let defaultCoords = [50.8638372, 4.3607629];

    // Initialize map
    let map = L.map(mapContainerId, {
        zoom: intialZoom, 
        center: defaultCoords
    });

    // map.getContainer().style.zIndex = 1;

    // Add tile layer to the Map
    L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
        minZoom: 1, // till you can zoom in
        maxZoom: 20, //till you can zoom out
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    })
        .addTo(map); // otherwise wont be in our map
    
    // fetch data and create markers
    fetch (apiEndPoint) //route path to transform data in JSON
        .then(data => data.json())
        .then(cities =>{
            // console.log(cities);
            cities.forEach(city => {
                console.log(city);
                // console.log("coucou " + city.city);
                if(city.lat != null && city.lon != null){
                    let coords = [city.lat, city.lon];
                    let marker = L.marker(coords).addTo(map);
                    // console.log(coords);
                    
                    let popup = `<div class="popup">
                                    <div>
                                        <h4>${city.eventTitle}</h4>
                                        <p>${city.name}</p>
                                        <p>${city.postCode} - ${city.city}</p>
                                    </div>
                                </div>`;
                    marker.bindPopup(popup);
                    // markers.addLayer(marker);
                    limits.push(coords);
                    map.addLayer(marker);
                }
                else{
                    let marker = L.marker(defaultCoords).addTo(map);
                    map.addLayer(marker);
                }
            });
            // console.log(limits);
            map.fitBounds(limits);
        })
        .catch((error)=> console.error("Error dowloading data",error))
}


/*********************** Tuto *************************************/

// Tuto: https://www.youtube.com/watch?v=N3hTwPvn_Xk

// Declare Paris coordinates
// let lat = 48.852969;
// let lon = 2.349903;
// limits = [];

// // Initialize map
// map = L.map("map", {
//     zoom: 13, 
//     center: [lat,lon] // center to Paris
// })

// // Set a tile layer
// L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
//     minZoom: 1, // till you can zoom in
//     maxZoom: 20, //till you can zoom out
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
// })
//     .addTo(map); // otherwise wont be in our map

// // Add marker
// let marker = L.marker([lat,lon]).addTo(map);

// // Use a jsonlist
// fetch("/addressList.json")
//     .then(data => data.json())
//     .then(cities =>{
//         // Use clustering plugin of leaflet to avoid superposition of marker when a lot in the same area.
//         // Create a group
//         let markers = L.markerClusterGroup();
//         //  When you create a marker, you have to put it into the cluster

//         // Then add clusters to map, you then have to suppress .addTo(map) for the individual markers (otherwise theyre will be duplicates)

//         for (let [city, content] of Object.entries(cities)){
//             // console.log(city, content);
//             let coords = [content.lat, content.lon]
//             // Create marker for each city
//             let marker = L.marker(coords).addTo(map);

//             // create pop-up on marker
//             marker.bindPopup(content.description);

//             /** If you want to add an image: 
//              * let popup = ` <div class="popup">
//              *                  <img src="/images/${content.image}" alt ="${city}" width="50" height= "50">
//              *                  <div>
//              *                      <h2>${city}</h2>
//              *                      <p>${content.description}</p>
//              *                  </div>
//              *                 </div>`
//              * marker.bindPopup(popup)
//              * */ 
//             limits.push(coords); // add marker to limits so it autmoatcly unzoom
//             markers.addLayer(marker); //add it in cluster
//         };

//         map.fitBounds(limits) // automaticaly unzoom to see all markers

//         // If you want to change icone image
//         let icone = L.icon({
//             iconUrl: "/images/filename",
//             iconSize: [25,41],
//             iconAnchor: [12.5, 41],
//             popupAnchor: [0,-41]
//         })

        // Then add it to marker after coords
        /** let marker = L.marker(coords, {
         *      icon: icone
         * }).addTo(map);
         */
            // })
// let cities = {
//     "Paris": {
//         "lat": 48.852969,
//         "lon": 2.349903,
//         "description": "Make love not war"
//     },

//     "Brest" :{
//         "lat":48.383,
//         "lon": -4.5,
//         "description": "Il n'y a pas de mauvais temps, que des mauvais vêtements!"
//     },

//     "Bruxelles" : {
//         "lat":50.8466,
//         "lon": 4.3528,
//         "description": "Best capital in the world"
//     }
// }



