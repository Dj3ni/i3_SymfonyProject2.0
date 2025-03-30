/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import "./styles/event_cards.css";
import "./styles/event_info.css";

const $ = require ("jquery");
window.jQuery = $;
window.$ = $

import "bootstrap"
import "bootstrap/dist/css/bootstrap.css"
import axios from "axios";



/***************** Hidden submenu nav *****************/ 
document.addEventListener("DOMContentLoaded",() => {
    const SUB_MENU = document.getElementById("subMenu");
    const AVATAR = document.querySelector(".avatar");
    // console.log(SUB_MENU);
    // console.log(AVATAR);

    AVATAR.addEventListener("click", function(){
        SUB_MENU.classList.toggle("open-menu");
    })
})

/********************* Ajax Search Form ****************/ 

const SEARCH_FORM = document.getElementById("SearchForm");
const DIV_RESULT = document.getElementById("SearchResult");
// console.log(SEARCH_FORM);
// console.log(DIV_RESULT);

// Listens to each modif on form's inputs, Ajax Search
SEARCH_FORM.addEventListener("input", function(){
    // console.log("Modify form");

    let formData = new FormData (SEARCH_FORM);

    axios.post("/events/search", formData)
        .then( response => {
                // console.log(response);
            // Clear previous response
            DIV_RESULT.innerHTML = "";
            // We don't need to parse the response, axios does it alone

            let arrayEvents = response.data; //It's already an array

            // I want to generate this html structure:

            arrayEvents.forEach(event => {
                console.log(event.eventType);
                // Create card div
                let cardDiv = document.createElement("div");
                cardDiv.classList.add("card");
                // cardDiv.style.width = "18rem";

                // Create Card Body
                let cardBody = document.createElement("div");
                cardBody.classList.add("card-body")

                // Title with link and add it to card body
                let cardTitle = document.createElement("h5");
                cardTitle.classList.add("card-title");
                let titleLink = document.createElement("a");
                titleLink.href = `/event/${event.id}`;
                titleLink.textContent = event.title
                cardTitle.appendChild(titleLink);
                cardBody.appendChild(cardTitle);

                // Event Type + image
                let cardTypeDiv = document.createElement("div");
                cardTypeDiv.classList.add("card-type");

                let cardEventType = document.createElement("h6")
                cardEventType.classList.add("card-subtitle", "mb-2", "text-body-secondary");
                cardEventType.textContent = event.eventType;
                let cardTypeImage = document.createElement("img");
                cardTypeImage.src = `/images/${event.eventType}.png`;
                cardTypeImage.alt = event.eventType;

                cardTypeDiv.appendChild(cardEventType);
                cardTypeDiv.appendChild(cardTypeImage);
                cardBody.appendChild(cardTypeDiv);

                // Horizontal rule
                let hr = document.createElement("hr");
                hr.classList.add("hr-xs");
                cardBody.appendChild(hr);

                // Card Text
                let cardText = document.createElement("p");
                cardText.classList.add("card-text");
                cardText.textContent = event.description;
                cardBody.appendChild(cardText);

                // Card links section
                let cardLinkDiv = document.createElement("div");
                cardLinkDiv.classList.add("card-link-div");

                let moreInfoLink = document.createElement("a");
                moreInfoLink.href = `/event/${event.id}`;
                moreInfoLink.classList.add("card-link");
                moreInfoLink.textContent = "More info";

                let subscribeLink = document.createElement("a");
                subscribeLink.href = `/event_subscription/${event.id}`;
                subscribeLink.classList.add("card-link");
                subscribeLink.textContent = "Subscribe";

                cardLinkDiv.appendChild(moreInfoLink);
                cardLinkDiv.appendChild(subscribeLink);
                // Admin control
                if (event.isAdmin) { 
                    let editLink = document.createElement("a");
                    editLink.href = `/update_event/${event.id}`;
                    editLink.classList.add("card-link");
                    editLink.textContent = "✏️";

                    let deleteLink = document.createElement("a");
                    deleteLink.href = `/delete_event/${event.id}`;
                    deleteLink.classList.add("card-link");
                    deleteLink.textContent = "❌";

                    cardLinkDiv.appendChild(editLink);
                    cardLinkDiv.appendChild(deleteLink);
                }

                cardBody.appendChild(cardLinkDiv);
                cardDiv.appendChild(cardBody);

                // Append all elements to the result
                DIV_RESULT.appendChild(cardDiv);
            });
        })
        .catch(error => {
            console.error("Error fetching events:", error);
        });

        // For debug
        // let ul = document.createElement("ul");
        
        // for(let element of arrayEvents){
        //     // console.log(element);
        //     let li = document.createElement("li");
        //     li.innerHTML = element.title;
        //     ul.appendChild(li);
        // }
        // DIV_RESULT.appendChild(ul);
    })
    

// // Handling the submit event, prevent reload and manage search
// SEARCH_FORM.addEventListener("submit", function(event){
//     event.preventDefault(); 
//     const SEARCH_INPUT = document.getElementById("SearchInput");
//     let query = SEARCH_INPUT.value;

//     if(query.lenght > 0){
//         // Redirect to page with results
//         window.location.href = "/event/events_show.html.twig" + JSON.encode(query);
//     }

// })

/********************* Google Map *******************/ 
const loadGoogleMapsApi = require('load-google-maps-api');class Map {

static loadGoogleMapsApi() {
    return loadGoogleMapsApi({ key: process.env.GOOGLEMAPS_KEY });
}  static createMap(googleMaps, mapElement) {
    return new googleMaps.Map(mapElement, {
    center: { lat: 45.520562, lng: -122.677438 },
    zoom: 14
    });
}
}export { Map };

document.addEventListener("DOMContentLoaded", function() {
    let mapElement = document.getElementById('map');
    
    Map.loadGoogleMapsApi().then(function(googleMaps) {
    Map.createMap(googleMaps, mapElement);
    });});