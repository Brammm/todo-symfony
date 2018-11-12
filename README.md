# Todo app

This is a small todo app to try out Symfony4

## Installation and setup

A local php version and docker are required.

- Clone this repository
- `docker-compose up` to run mailcatcher and mysql for this app
- `bin/console d:m:m` to run all migrations and get the database started
- `bin/console server:start` to start a local version
- Copy .env.dist to .env, you can use `DATABASE_URL=mysql://root:root@127.0.0.1:3309/todo` and `MAILER_URL=smtp://localhost:1025?encryption=&auth_mode=` 


## Mailcatcher

Mails are sent to mailcatcher instead of an actual mailbox. Check the interface at http://localhost:1080/. 