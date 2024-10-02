```bash
X   X  DDDD  I  RRRR
 X X   D   D I  R   R
  X    D   D I  RRRR
 X X   D   D I  R R
X   X  DDDD  I  R  RR
```

# xdir-back-v2
Backend for User and Role Administration and Registration

# Initial Configuration of xdir-back-v2

This document provides a step-by-step guide to setting up the development environment for the User and Role Administration and Registration backend, xdir-back-v2. Follow the instructions carefully to ensure a correct configuration of the project. PHP8.2

## Prerequisites

Before starting, make sure you have Git and Composer installed on your system. These tools are essential for cloning the repository and managing the PHP dependencies of the project.

## Clone the Repository

To obtain the source code of the project, run the following command in your terminal:
```bash
git clone git@github.com:XIMDEX/xdir-back-v2.git
```

This command clones the repository into a new folder called xdir-back-v2 in your current directory. Then, you need to run cd xdir-back-v2 to access the directory.


## Install Dependencies
```bash
composer install
```
This command reads the composer.json file, downloads the required dependencies, and installs them in the vendor directory.

## ENV

Copy the .env.example file to create your .env file:
```bash
cp .env.example .env
```
And configure the environment variables, especially the database variables. To load these new settings, run the following command:
```bash
php artisan optimize
```
And then verify the routes to ensure everything is correct:
```bash
php artisan route:list
```
## Migrate
Run the migration:
```bash
php artisan migrate
```
Now, test the /register route using the following data:
```bash
{
  "email": "testXdir@ximdex.com",
  "password": "test123456",
  "name": "test",
  "surname": "text surname",
  "birthdate": "2020-10-10"
}
```
In the response, you will receive a token that will allow you to avoid registering a real email address (Remember to have the app in debug mode in the env file to get this response).
## Keys
```bash
php artisan key:generate
```
Also, generate the keys for Passport:
```bash
php artisan passport:keys
```
And ensure they have the correct permissions:
```bash
sudo chown www-data:www-data storage/oauth-public.key storage/oauth-private.key
```
Finally, generate the client secret:
```bash
php artisan passport:client --personal.
```
You will receive two variables that you need to save in the .env file along with the assigned name:
```bash
PASSPORT_PERSONAL_ACCESS_CLIENT_ID="your_client_id"
PASSPORT_SECRET="your_client_secret"
PASSPORT_TOKEN_NAME="client_name"
```
And finish with a php artisan optimize command.
