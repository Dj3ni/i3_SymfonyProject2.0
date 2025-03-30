# Features

What I want:

MVP1: Events features: crud and subscription to an event
MVP2: Subscribe a club and members management
MVP3: Manage boardgame Library
MVP4: Create and subscribe to a game session


## MVP1 features
<!-- - Login / Logout / Sign up -> ok -->
<!-- - Events list -> ok -->
<!-- - Event details -> ok -->
- search for an event with filters-> ok but with ajax is better
<!-- - Subscribe to an event if logged-in -> ok -->
<!-- - if admin: update and create event + redirect when send ->ok -->
<!-- - contact page + contact form -> ok -->
<!-- - address ->ok -->

To do:
<!-- - finish wireframe -> ok -->
<!-- - endEvent must be later than startEvent -> ok -->
<!-- - add occurency for events -> ok -->
<!-- - add default value for fee = free -> ok -->
- events search -> Ajax search ok, Ajax search with filters to do 
<!-- - create address entity and link it with User and Gaming Place -> ok -->
- manage users + user access for admin
<!-- - message when form submitted ->ok -->
<!-- - contact page ->ok -->
- create validation rules for forms (email,pwd,...)
- upload d'images -> bug to fix with avatar

### Bonus
- sync agenda with google calendar
<!-- - sync address with a map (https://www.youtube.com/watch?v=1ZmHG3cqPAs) for tuto ->ok -->

### Comments
- All entities, fixtures and relations for MVP1 ok

### Bugs known
<!-- - Impossible to delete child event-> ok -->
- improve email constraints, no error message appears when errors in all forms (except dateEnd)
- no message when logged-out
- serialization problem with avatar and Vich uploader

## MVP2 features

- create/update and search a Club
- user can subscribe to a club
- club can create an event?

## MVP3 Features

- CRUD boardgame Library
- link a library to a club

## MVP4 Features

- select a game in a library and create and subscribe to a game session
