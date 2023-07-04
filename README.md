# Badminton League Tables

Provides a way for badminton leagues to manage the league scoreboard with constant updates. The system provides a 
certain level of data integrity and has the ability to warn users about incomplete scorecards or incorrect information.

## ToDo
- Add a management page to the admin area
  - Trigger a manual "UpdateScoreboard" job
  - Trigger a manual "UpdateScoreboards" job
  - Manually create users
- Send out emails to registered users
  - Allow users to register for the purposes of receiving emails

## Running
1. Clone the repository
2. Environment file
   1. Copy the `.env.example` to `.env`
   2. Change the passwords and other required fields
3. `docker-compose build`
5. `docker-compose up -d`

### Data Import
If there is data to import, then this can be done by running the 
following command inside the container:
```bash
docker-compose exec backend sh
cd api/
php artisan import
```

This will import the CSV data in the `/import` folder in the Docker
container (bind mounted through docker-compose). 

## Technologies
This stack relies on a few different technologies to run:
- MariaDB
- Redis
- Lumen PHP Framework
- Vue.JS JavaScript Framework

The backend API is REST based and aims to keep inline with REST standards. The frontend has heavy emphasis on caching
data from the API to reduce the number of network calls, albeit at the expense of being slightly more memory hungry

## Docker
The whole stack is designed to be run in a containerized environment. Currently `docker-compose` is the best way to run
the system. Persistent data is stored using bind mounts and private networking is utilized to separate services and 
only expose what is necessary: the front facing Nginx container.

Avoiding CORS issues is done via the reverse proxy Nginx as both the frontend and backend reside on the same subdomain.
The frontend runs on `/` and the backend is running on `/api`.

Before running, the `env` file must be edited to reflect the specifics of a league, the rest of the configuration is 
done at run and build time.
