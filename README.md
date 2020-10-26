<h1 align="center">Messenger</h1>
<p>Simple messenger with authentication and end-to-end encryption</p>
<p>Bult on Laravel Framework 7.4.0</p>

<hr>
How to setup

1. Clone repository\
    `git clone https://github.com/Mutad/Messenger.git`
2. Open terminal inside project
3. Install Composer Dependencies\
    `composer install`
4. Create a copy of your .env file\
    `cp .env.example .env`
5. Generate an app encryption key\
    `php artisan key:generate`
6. Generate a passport encryption key\
    `php artisan passport:install`
7. In the .env file, add database information to allow Laravel to connect to the database
8. Migrate the database
9. _Optional_: Seed the database
10. To run it use\
    `php artisan serve`

<hr>

TODO:
- [x] User registration and authentication
  - [x] Simple registration without security
  - [x] JSON Web Tokens 
  - [x] Secured authorization
- [x] Send messages
- [ ] Encrypt Messeges
- [x] Add Channels (group chats)
- [ ] Add Groups

