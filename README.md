Entrili XApi
============

Experimental Forks based on Symfony 5 & https://github.com/php-xapi

---

### 1) Installing - Docker

create your ./.env.local file from ./.env

Launch Docker, from ./docker/

    docker-compose up -d

---

### 2) Installing - Composer + DB

In a terminal, launch the following command to enter into container

    docker exec -ti xapi_www bash

Then run:

    php bin/console doctrine:schema:create

---

### 3) Getting started with Entrili XApi

Endpoints would be

- http://127.0.0.1:8741/lrs/activities
- http://127.0.0.1:8741/lrs/activities/state
- http://127.0.0.1:8741/lrs/statements