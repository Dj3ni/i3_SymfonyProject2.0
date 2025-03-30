# Project Context

I have to build a Symphony project for school and was asked that it contains:
- user sessions / login - logout
- a DB with crud fonctions
- search form 

# Project Title

My website is for boardgamers who want to subscribe to a boardgame club. It will be possible for them to consult their events, their game libraries and subscribe to a game session.

## Features

What I want:

MVP1: Events features: crud and subscription to an event<br>
MVP2: Subscribe a club and members management<br>
MVP3: Manage boardgame Library<br>
MVP4: Create and subscribe to a game session<br>

## Dependencies

### Webpack

### Calendar JS
Calendar doc: 
- https://fullcalendar.io/docs/date-display
- https://jquense.github.io/react-big-calendar/examples/index.html?path=/docs/about-big-calendar--page

### Leaflet for OpenStreetMap

- https://leafletjs.com/

With plugins: 
- MapTiler (for geocoding)
- Leaflet MarkerCluster (not working for the moment) 

### Vich uploader
https://github.com/dustin10/VichUploaderBundle

## The project now

For now, only the MVP1 features are implemented. I pushed them a lot further that I initially thought because I wanted my MVP1 to be as complete as possible so all the other features would be "bonuses". 

## The project tomorrow

- A lot of the project is for admin users, so I want to develop a back office Interface with easy admin 4. It will really improve the UX. I didn't do it before because, as a learner, it was asked from me to build everything "from scratch". 

- Add features for the map search:
    1. geolocalisation (https://developer.mozilla.org/fr/docs/Web/API/Geolocation_API)
    2. Searchbar in the map
    3. Bugfix for clusters marker

- Sync the personal event calendar with a google calendar.

- to be able to send a mail (if wanted) when a user subscribes to an event or when an event is cancelled

- add boardgame Clubs

<!-- 
## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system. 

### Prerequisites

What things you need to install the software and how to install them

```
Give examples
```

### Installing

A step by step series of examples that tell you how to get a development env running

Say what the step will be

```
Give the example
```

And repeat

```
until finished
```

End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
-->
