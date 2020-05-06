# Installing on a server
Once you do all of this

```
vi .env
composer install
npm install
npm run dev
php artisan migreate



php artisan tinker
$user = \App\User::create([
'email'=>'paulb@savagesoft.com',
'name'=>'Paul',
'password'=>bcrypt('secret')
]);

php artisan app:set-initial-permissions
php artisan app:set-user-roles
```

## Setup Passport

```
php artisan passport:keys --force
php artisan passport:install --force
```

### Save the Keys

In your `.env` file put

```
PERSONAL_CLIENT_ID=1
PERSONAL_CLIENT_SECRET=mR7k7ITv4f...
PASSWORD_CLIENT_ID=2
PASSWORD_CLIENT_SECRET=FJWQRS3PQj...

```

The `PASSWORD_CLIENT_ID`, andd `PASSWORD_CLIENT_SECRET` will be used by the client or its proxy 
to authenticate


### Create accessToken for API
  
```
  php artisan tinker
  >>> $user = User::find(1)
  >>> $token = $user->createToken('Hackerpair')->accessToken
  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...Um1Py-KdjXfQ"
```

### Added token to .env for testing


The keys are stored in storage/\*key


### Test the Server to Server API


In a empty directory

```
composer require guzzlehttp/guzzle
```

In index.php

You will need to adjust the `base_uri`

``````
<?php
  
require "vendor/autoload.php";

$accessToken = "eyJ0eXAiOiJKV1QiL...";

$client = new GuzzleHttp\Client([
    'base_uri' => 'http://self-report.test/api/',
    'verify' => false,  // ONLY FOR SELF SIGNED SSL CERT

]);

$response = $client->request('GET', 'user', [
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$accessToken,
    ]
  ]
);
echo $response->getBody();
``````

Now

``````
php index.php
``````

And you should see most of the first record from the users table.

If you get an error that Guzzel Could not resolve the API server add 127.0.0.1 to your DNS, on apple
System Preferences -> Network -> Advancced -> DNS



### Test Logging in

```
curl -X POST http://self-report.test/oauth/token  -b cookies.txt -c cookies.txt -D headers.txt -H 'Content-Type: application/json' -d '{"username":"your-email@bigmail.com","email":"your-email@bigmail.com","password":"secret","grant_type":"password","scope":"*"}'
```

# Setup first Organization

1. Login
2. Select Admin->Organization->Add
3. 

