# XDIR: User and Role management (Backend)

XDIR is acomprehensive user management system (registration, deactivation, and editing; credential recovery). This system also allows for the association of roles and organizations, as well as services within the XIMDEX Platform. 

This document provides a step-by-step guide to setting up the development environment for the User and Role management and registration backend, `xdir`. Follow the instructions carefully to ensure the proper configuration of the project.
PHP8.2

## Prerequisites

Before starting, make sure you have Git and Composer installed on your system. These tools are essential to clone the repository and manage the PHP dependencies of the project.

## Clone the repository

To get the project source code, run the following command in your terminal:
```bash
git clone git@github.com:XIMDEX/xdir.git
```

This command clones the repository into a new folder named xdir in your current directory.
Then, you need to run `cd xdir` to access the directory.

## Switch to the development branch

To work with the latest development version, switch to the develop branch:
```bash
git checkout develop
```

## Install dependencies
```bash
composer install
```
This command reads the composer.json file, downloads the required dependencies, and installs them into the vendor directory.

## ENV

Copy the .env.example file to create your .env
```bash
cp .env.example .env
```
And configure the environment variables, especially the database ones.
For all these new settings to be loaded, you need to run the following command:
```bash
php artisan optimize
```
Then, we can verify the routes to make sure everything is correct:
```bash
php artisan route:list
```

## Migrate

You need to run the migration:
```bash
php artisan migrate
```

Now, test the /register route. You can use the following data:
```bash
{
  "email": "testXdir@mydomain.com",
  "password": "Test12345",
  "name": "test",
  "surname": "text for surname",
  "birthdate": "2020-10-10"
}
```

In the response, you will receive a token that will allow you not to have to register a real email (Remember to have the app in debug mode, in the env, to get this response).

## Keys
```bash
php artisan key:generate
```
You also need to generate the keys for passport
```bash
php artisan passport:keys
```
And ensure they have the correct permissions:
```bash
sudo chown www-data:www-data storage/oauth-public.key storage/oauth-private.key
```

We would need to generate the key:
```bash
php artisan passport:client --personal.
```

In the end, you will receive two variables that must be stored in the .env file along with the assigned name:
```bash
PASSPORT_PERSONAL_ACCESS_CLIENT_ID="your_client_id"
PASSPORT_SECRET="your_client_secret"
PASSPORT_TOKEN_NAME="client_name"
```
And finish with a php artisan optimize.

## Email Verification and Login

Verify the email: To simulate email verification, access the /email/verify/{token} route, where {token} is the verification token you previously obtained.

Test login: Once the email is verified, you can proceed to test login through the /login route using tools like Postman or cURL, providing the user credentials (email and password).
```




