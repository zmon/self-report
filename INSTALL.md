# Installing on a server
Once you do all of this

```
vi .env
composer install
npm install
php artisan migreate

php artisan tinker
        $user = \App\User::create([
            'email' => 'your-email@bigmail.com',
            'name' => 'Your Name',
            'password' => bcrypt('secret')
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
    'base_uri' => 'https://cms.apskc.ldev/api/',
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
curl -X POST http://covidselfreporting.test/oauth/token  -b cookies.txt -c cookies.txt -D headers.txt -H 'Content-Type: application/json' -d '{"username":"your-email@bigmail.com","email":"your-email@bigmail.com","password":"secret","grant_type":"password","scope":"*"}'
```

# Add more tables with CRUD

## Create and Run Migration

```
php artisan make:migration create_self_reports_table
php artisan migrate

php artisan make:crud self_reports  --display-name="Self Reports" --grid-columns="created_at:symptom_start_date:exposed:state:zipcode:ResponseReferenceId"   --force
```

Folow the instructions in Doc/CRUD/self_report.md

# Building it yourself

### Notes that need to be put someplace else.

JWS Password Grant - Now broswer does not need to know secret

```
git diff e3e0cd6 82ec158
diff --git a/app/Http/Kernel.php b/app/Http/Kernel.php
index 4d3b98f..8fe7b58 100644
--- a/app/Http/Kernel.php
+++ b/app/Http/Kernel.php
@@ -58,7 +58,6 @@ class Kernel extends HttpKernel
         'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
         'can' => \Illuminate\Auth\Middleware\Authorize::class,
         'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
-        'password-grant' => \App\Http\Middleware\InjectPasswordGrantCredentials::class,
         'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
         'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
         'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
diff --git a/app/Http/Middleware/InjectPasswordGrantCredentials.php b/app/Http/Middleware/InjectPasswordGrantCredentials.php
deleted file mode 100644
index 98c6f22..0000000
--- a/app/Http/Middleware/InjectPasswordGrantCredentials.php
<?php

// From https://laracasts.com/discuss/channels/code-review/api-authentication-with-passport
//   resolves issue of SPA having to send a secret
// General http://esbenp.github.io/2017/03/19/modern-rest-api-laravel-part-4/

namespace App\Http\Middleware;

use DB;
use Closure;

class InjectPasswordGrantCredentials
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
	// For some reason in Laravel 7 env() does not work here
        $password_client_id = data_get(config(),'passport.password_client_id',false);

        if ($request->grant_type == 'password') {
            $client = DB::table('oauth_clients')
                ->where('id', $password_client_id)
                ->first();


            $request->request->add([
                'client_id' => $client->id,
                'client_secret' => $client->secret,
            ]);
        }

        return $next($request);
    }
}
diff --git a/app/Providers/AuthServiceProvider.php b/app/Providers/AuthServiceProvider.php
index 0ace75f..5ea0865 100644
--- a/app/Providers/AuthServiceProvider.php
+++ b/app/Providers/AuthServiceProvider.php
@@ -2,7 +2,6 @@
 
 namespace App\Providers;
 
-use Route;
 use Laravel\Passport\Passport;
 use Illuminate\Support\Facades\Gate;
 use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
@@ -28,23 +27,5 @@ class AuthServiceProvider extends ServiceProvider
         $this->registerPolicies();
 
         Passport::routes();
-
-        Route::post('oauth/token', [
-            'middleware' => 'password-grant',
-            'uses' => '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken'
-        ]);

```

Create config/passport.php

```
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    */

    'private_key' => env('PASSPORT_PRIVATE_KEY'),

    'public_key' => env('PASSPORT_PUBLIC_KEY'),

    'password_client_id' => env('PASSWORD_CLIENT_ID'),

];
```

This should test it

```
curl -X POST http://dev-7.test/oauth/token  -b cookies.txt -c cookies.txt -D headers.txt -H 'Content-Type: application/json' -d '{"username":"your-email@bigmail.com","email":"your-email@bigmail.com","password":"secret","grant_type":"password","scope":"*"}'
```

## First Step Install Laravel

```
composer create-project --prefer-dist laravel/laravel dev "6.*"
```

### Create Git Repo

```
cd dev
git init .
git add .
git commit -m 'Installed Laravel "7.*"'
```


## Setup Auth and install Vue
 
```
composer require laravel/ui
php artisan ui vue
npm install && npm run dev
php artisan ui:auth
npm run dev
```   

### Setup .env and Database

Create a database and user, then update the connection `.env`

Create for both dev and Test
```
CREATE USER 'my_db'@'localhost' IDENTIFIED BY 'my_db';
ALTER USER 'my_db'@'localhost' IDENTIFIED WITH MYSQL_NATIVE_PASSWORD BY 'my_db';
CREATE DATABASE my_db;
GRANT ALL PRIVILEGES ON my_db.* to 'my_db'@'localhost' WITH GRANT OPTION;
```


## Setup .env

```
APP_NAME="Covid Self Reporting 0.0" 
APP_ENV=local
APP_KEY=base64:MOflvfnUcXQhv5u+vcv....Nlzk3cI5fHO0=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opencity
DB_USERNAME=opencity
DB_PASSWORD=opencity

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## Setup .env.testing

```
APP_NAME="Covid Self Reporting Test 0.0"
APP_ENV=local
APP_KEY=base64:MOflvfnUcXQhv5u+...VruNlzk3cI5fHO0=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opencity_tst
DB_USERNAME=opencity_tst
DB_PASSWORD=opencity_tst

BROADCAST_DRIVER=log
CACHE_DRIVER=sync
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=array

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```


### Do Initial Migration for users, password_resets and failed_jobs

```
php artisan migrate
```

### Start Valet

```
valet install
valet link
valet open
```

### Commit
vi .gitignore  -- add .env.testing
git add .
git commit -m 'Added Vue, Auth'
 
## Create first user
In tinker

```
php artisan tinker
$user = \App\User::create([
    'email' => 'your-email@bigmail.com',
    'name' => 'Your Name',
    'password' => bcrypt('secret')
]);
```


# Java Script Code Spliting

Followed: http://channingdefoe.com/vuejs-code-splitting-in-laravel-webpack/

```
npm install babel-plugin-syntax-dynamic-import
```

Create a file called .babelrc in your root Laravel installation and enter the following:

```
{
    "plugins": ["syntax-dynamic-import"]
}

```
In your webpack.mix.js file that is in your root Laravel installation enter the following:

```
mix.webpackConfig({
    output: {
        // Chunks in webpack
        publicPath: '/',
        chunkFilename: 'js/components/[name].js',
    },
});
```


# Install mix copy watched

```
npm install babel-plugin-syntax-dynamic-import
```

Update your webpack.mix.js to 

```
const mix = require('laravel-mix');

require('laravel-mix-copy-watched');
  
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output: {
        // Chunks in webpack
        publicPath: '/',
        chunkFilename: 'js/components/[name].js',
    },
});


mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');

mix.copyDirectoryWatched('resources/img/**/*', 'public/img', { base: 'resources/img' });
mix.copyDirectoryWatched('resources/css/**/*', 'public/css', { base: 'resources/css' });

if (mix.inProduction()) {
    mix.version();
}
```

Create needed directories

```
mkdir resources/img resources/css
```


## Setup passport


[A modern REST API in Laravel 5 Part 4: Authentication using Laravel Passport - Esben Petersen](http://esbenp.github.io/2017/03/19/modern-rest-api-laravel-part-4/) has a good 20minute intro to OAuth 2.


Grant type |	Used for
-----------|-----------
Client Credentials	| When two machines need to talk to each other, e.g. two APIs
Authorization Code| 	This is the flow that occurs when you login to a service using Facebook, Google, GitHub etc.
Implicit Grant |	Similar to Authorization Code, but user-based. Has two distinct differences. Outside the scope of this article.
**Password Grant** |	When users login using username+password. The focus of this article.
**Refresh Grant** |	Used to generate a new token when the old one expires. Also the focus of this article.


This instructions from 5.7 combines API Authentication and Passport [API Authentication (Passport) - Laravel - The PHP Framework For Web Artisans](https://laravel.com/docs/5.7/passport)

The 5.8 instructions for API Authentication do not include Passport.

Background: Talks about 'App\\Model' => 'App\\Policies\\ModelPolicy',[laravel 5.5 authorization - Sunnoy - Medium](https://medium.com/sunnoy/laravel-5-5-authorization-7a8e555b5500)

## Install

``````
composer require laravel/passport
php artisan migrate
``````

*  add the Laravel\Passport\HasApiTokens trait to your App\User model
   * Add use
   * Add HasApiTokens  

```
use Laravel\Passport\HasApiTokens;
   .
   .
   .
   use HasApiTokens, Notifiable, HasRoles;
```

*  Add passport routes to the AuthServiceProvider::boot()
   * Add use
   * Add Passport::routes()


```
use Laravel\Passport\Passport;
   .
   .
   .
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
```
*  In config/auth.php set the api driver to passport

```
guards' => [
   .
   .    
   .    
    'api' => [
        'driver' => 'passport',
```



# Setup Passport

````
php artisan passport:keys --force
php artisan passport:install --force
````

## Save the Keys

In your `.env` file put

```
PERSONAL_CLIENT_ID=1
PERSONAL_CLIENT_SECRET=mR7k7ITv4f...
PASSWORD_CLIENT_ID=2
PASSWORD_CLIENT_SECRET=FJWQRS3PQj...

```

The `PASSWORD_CLIENT_ID`, andd `PASSWORD_CLIENT_SECRET` will be used by the client or its proxy 
to authenticate


## Create accessToken for API
  
```
  php artisan tinker
  >>> $user = User::find(1)
  >>> $token = $user->createToken('Hackerpair')->accessToken
  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...Um1Py-KdjXfQ"
```

## Added token to .env for testing




The keys are stored in storage/\*key


## Front end quick start

```
php artisan vendor:publish --tag=passport-components
```

### Remove the script
If you do not remove the script tags you will get errors in the console  `"Failed to resolve async component"`

```
resources/js/components/passport/AuthorizedClients.vue
resources/js/components/passport/Clients.vue
resources/js/components/passport/PersonalAccessTokens.vue
```




* run `npm run dev`

* Setup aps.js

```
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);


require("./components");

require("./mixins");
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
```

* Add the following to resources/js/components.js

```
/*
    Passport
 */
Vue.component('passport-clients', () => import(/* webpackChunkName:"passport-clients" */ './components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', () => import(/* webpackChunkName:"passport-authorized-clients" */ './components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', () => import(/* webpackChunkName:"passport-personal-access-tokens" */ './components/passport/PersonalAccessTokens.vue'));

// Vue.component( 'passport-clients', require('./components/passport/Clients.vue'));
// Vue.component( 'passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
// Vue.component( 'passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

```

* Create mixins.js

```
// Custom js

Vue.mixin({
    methods: {
        isDefined(obj) {
            return typeof obj !== "undefined" && obj !== null;
        },
        formatNumber(value, precision) {
            return parseFloat(value)
                .toFixed(precision || 0)
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        isUndefinedOrEmpty(obj) {
            return (
                typeof obj === "undefined" ||
                obj === null ||
                (typeof obj === "string" && !obj.trim())
            );
        },
        getBoolean(value) {
            if (typeof value == "string") {
                value = value.toLowerCase();
            }
            switch (value) {
                case true:
                case "true":
                case 1:
                case "1":
                case "on":
                case "yes":
                    return true;
                default:
                    return false;
            }
        },
        scrollToTop() {
            document
                .querySelector("body")
                .scrollIntoView({behavior: "smooth"});
        }
        /*scrollToFirstError() {
            var firstErrorEle = this.$el.querySelector('.has-error');
            if(firstErrorEle) {
                firstErrorEle.scrollIntoView({behavior: 'smooth'});
            }
        }*/
    }
});

Vue.directive("tooltip", function (el, binding) {
    jQuery(el).on("click", function (e) {
        e.preventDefault();
    });
    jQuery(el).tooltip({
        title: jQuery(".help-text", el).html(),
        html: true,
        placement: "top",
        trigger: "hover focus"
    });
});

```


* Add the following to home.blade.php

```
    <div class="row">
        <ul>
            <li><passport-clients></passport-clients>
            <li><passport-authorized-clients></passport-authorized-clients>
            <li><passport-personal-access-tokens></passport-personal-access-tokens>
        </ul>
    </div>
```

Compile the Passport VueJs components

```
npm run dev
```




### Give it a try


In a empty directory

``````
composer require guzzlehttp/guzzle
``````

In index.html

``````
<?php
  
require "vendor/autoload.php";

$accessToken = "eyJ0eXAiOiJKV1QiL...";

$client = new GuzzleHttp\Client([
    'base_uri' => 'https://cms.apskc.ldev/api/',
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



# Added spatie/laravel-permission

https://github.com/spatie/laravel-permission#using-multiple-guards

## Table Rename


* composer require spatie/laravel-permission


## Install

```
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan migrate
```

Update User model

```
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, Notifiable, HasRoles;
```

## Create initial rules

Create console command

```
php artisan make:command SetInitialPermissions
```

Put the following in it

```
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\InitialPermissions;


class SetInitialPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbv:set-initial-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial permissions and roles, asign to firt user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $perms = new InitialPermissions();

    }
}
```

Add the following to Kernel.php

```
'App\Console\Commands\SetInitialPermissions',
```

Create app/Lib/InitialPermissions.php

```
<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 2019-05-31
 * Time: 23:49
 */

namespace App\Lib;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class InitialPermissions
{
    function __construct()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        Role::findOrCreate('super-admin');


        Permission::findOrCreate('always fail');

//        Permission::findOrCreate('client');
//        Permission::findOrCreate('contractor');


        $role = Role::findOrCreate('cant');

        $role->givePermissionTo(['always fail']);


        $role = Role::findOrCreate('only index');
        // For Testing
//        $role->givePermissionTo(['department index']);
//        $role->givePermissionTo(['department index']);
//        $role->givePermissionTo(['service_type index']);


        $role = Role::findOrCreate('Client Admin');
//        $role->update(['can_assign' => true]);
        $role->givePermissionTo([

            

        ]);

        
        $role = Role::findOrCreate('read-only');

        $role->givePermissionTo([

        ]);


    }
}
```




Create commane SetUserRoles.php

```
php artisan make:command SetUserRoles
```

Put in it

```
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\InitialRoles;

class AssigningRolesToUsers extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbv:set-user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roles = new InitialRoles();
    }
}
```

Add the following to Kernel.php

```
'App\Console\Commands\SetUserRoles',
```

```
<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 2019-05-31
 * Time: 23:49
 */

namespace App\Lib;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class InitialRoles
{

    function __construct()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->setRole('your-email@bigmail.com', 'super-admin');

    }

    private function setRole($email,$role) {
        print "Setting $email to $role\n";
        $user = User::where('email', $email)->first();
        $user->assignRole($role);
    }

}

```

See console command 

```
php artisan app:set-initial-permissions 
php artisan app:set-user-roles
```

## Setup so super-admin role can do anything

See https://docs.spatie.be/laravel-permission/v2/basic-usage/super-admin/

In AuthServiceProvider.php

```
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
```

# Set Hash driver to argon2id

In `config/hashing.php`

```
'driver' => 'argon2id',

```


# Password Strength

See https://github.com/skegel13/vue-password and https://github.com/bjeavons/zxcvbn-php

```
npm install --save vue-password
npm install --save zxcvbn
```

Globally register the compoonent in resources/js/components.js

```
/*
     SUPPORT FUNCTIONS
*/

import VuePassword from "vue-password";
// Password strength library
Vue.component("VuePassword", () => import("vue-password"));
```

## Invite 

Many things were changed to make it work - might be easier to tar them up and extract

```
	new file:   app/Http/Controllers/ChangePasswordController.php
	new file:   app/Http/Controllers/HistoryApi.php
	new file:   app/Http/Controllers/HistoryController.php
	new file:   app/Http/Controllers/InviteApi.php
	new file:   app/Http/Controllers/InviteController.php
	new file:   app/Http/Controllers/PasswordResetApi.php
	new file:   app/Http/Controllers/PasswordResetController.php
	new file:   app/Http/Controllers/PasswordStrengthApi.php
	new file:   app/Http/Controllers/RoleApi.php
	new file:   app/Http/Controllers/RoleController.php
	new file:   app/Http/Controllers/RoleDescriptionApi.php
	new file:   app/Http/Controllers/RoleDescriptionController.php
	new file:   app/Http/Controllers/RoleHasPermissionApi.php
	new file:   app/Http/Controllers/RoleHasPermissionController.php
	new file:   app/Http/Controllers/UserApi.php
	new file:   app/Http/Controllers/UserController.php
	new file:   app/Http/Controllers/UserFormRequest.php
	new file:   app/Http/Controllers/UserIndexRequest.php
	new file:   app/Http/Controllers/UserRequest.php
	new file:   app/Http/Controllers/UserRoleApi.php
	new file:   app/Http/Controllers/UserRoleController.php
	new file:   app/Http/Requests/ChangePasswordRequest.php
	new file:   app/Http/Requests/InviteEditRequest.php
	new file:   app/Http/Requests/InviteFormRequest.php
	new file:   app/Http/Requests/InviteIndexRequest.php
	new file:   app/Http/Requests/InvitePasswordRequest.php
	new file:   app/Http/Requests/InviteStoreRequest.php
	new file:   app/Http/Requests/PasswordResetFormRequest.php
	new file:   app/Http/Requests/PasswordResetIndexRequest.php
	new file:   app/Http/Requests/PasswordStrengthRequest.php
	new file:   app/Http/Requests/RoleIndexRequest.php
	new file:   app/Http/Requests/RoleStoreRequest.php
	new file:   app/Http/Requests/UserRoleFormRequest.php
	new file:   app/Http/Requests/UserRoleIndexRequest.php
	new file:   app/Invite.php
	modified:   app/Lib/InitialPermissions.php
	new file:   app/Mail/InviteCreated.php
	new file:   app/Role.php
	new file:   app/RoleDescription.php
	new file:   app/RoleHasPermission.php
	new file:   app/Traits/RecordSignature.php
	new file:   app/helpers.php
	modified:   composer.json
	modified:   composer.lock
	modified:   config/app.php
	modified:   config/mail.php
	new file:   database/migrations/2018_11_16_233502_create_invites_table.php
	new file:   database/migrations/2019_05_12_144309_add_active_to_users.php
	modified:   package-lock.json
	modified:   package.json
	modified:   public/css/app.css
	new file:   resources/img/icons/help.svg
	new file:   resources/img/icons/square.svg
	new file:   resources/img/icons/success.svg
	new file:   resources/img/icons/warning.svg
	modified:   resources/js/components.js
	new file:   resources/js/components/PasswordResetForm.vue
	new file:   resources/js/components/RoleDescription/RoleDescriptionForm.vue
	new file:   resources/js/components/RoleDescription/RoleDescriptionGrid.vue
	new file:   resources/js/components/RoleDescription/RoleDescriptionShow.vue
	new file:   resources/js/components/SS/CalendarPopUp.vue
	new file:   resources/js/components/SS/DatePicker.vue
	new file:   resources/js/components/SS/DspBoolean.vue
	new file:   resources/js/components/SS/DspDate.vue
	new file:   resources/js/components/SS/DspDecimal.vue
	new file:   resources/js/components/SS/DspDollar.vue
	new file:   resources/js/components/SS/DspText.vue
	new file:   resources/js/components/SS/DspTextArea.vue
	new file:   resources/js/components/SS/FldCheckBox.vue
	new file:   resources/js/components/SS/FldInput.vue
	new file:   resources/js/components/SS/FldState.vue
	new file:   resources/js/components/SS/FldTextArea.vue
	new file:   resources/js/components/SS/FldTextEditor.vue
	new file:   resources/js/components/SS/ModalWindow.vue
	new file:   resources/js/components/SS/OnClickOutside.vue
	new file:   resources/js/components/SS/SearchFormGroup.vue
	new file:   resources/js/components/SS/SsGrid.vue
	new file:   resources/js/components/SS/SsGridColumnHeader.vue
	new file:   resources/js/components/SS/SsGridPagination.vue
	new file:   resources/js/components/SS/SsGridRows.vue
	new file:   resources/js/components/SS/SsPaginationLocation.vue
	new file:   resources/js/components/SS/StdFormGroup.vue
	new file:   resources/js/components/SS/UiFieldView.vue
	new file:   resources/js/components/SS/UiPickRoles.vue
	new file:   resources/js/components/SS/UiSelectPickOne.vue
	new file:   resources/js/components/change_password/ChangePasswordForm.vue
	new file:   resources/js/components/invite/CreatePasswordForm.vue
	new file:   resources/js/components/invite/InviteGrid.vue
	new file:   resources/js/components/role_has_permissions/RoleHasPermissionForm.vue
	new file:   resources/js/components/role_has_permissions/RoleHasPermissionGrid.vue
	new file:   resources/js/components/role_has_permissions/RoleHasPermissionShow.vue
	new file:   resources/js/components/roles/RoleForm.vue
	new file:   resources/js/components/roles/RoleGrid.vue
	new file:   resources/js/components/roles/RoleShow.vue
	new file:   resources/sass/_crud-variables.scss
	new file:   resources/sass/_crud.scss
	new file:   resources/sass/crud-app.scss
	new file:   resources/sass/main.scss
	new file:   resources/views/change-password/change_password.blade.php
	new file:   resources/views/components/select-pick-one.blade.php
	new file:   resources/views/components/std-form-group.blade.php
	new file:   resources/views/components/std-show-field.blade.php
	new file:   resources/views/components/std-show-raw-field.blade.php
	new file:   resources/views/emails/invite-user.blade.php
	new file:   resources/views/helpers/select-pick-one.blade.php
	modified:   resources/views/home.blade.php
	new file:   resources/views/invite/create.blade.php
	new file:   resources/views/invite/create_password.blade.php
	new file:   resources/views/invite/edit.blade.php
	new file:   resources/views/invite/index.blade.php
	new file:   resources/views/invite/print.blade.php
	new file:   resources/views/invite/show.blade.php
	new file:   resources/views/layouts/bottom.blade.php
	new file:   resources/views/layouts/crud-master.blade.php
	new file:   resources/views/layouts/crud-nav.blade.php
	new file:   resources/views/role-description/create.blade.php
	new file:   resources/views/role-description/edit.blade.php
	new file:   resources/views/role-description/index.blade.php
	new file:   resources/views/role-description/print.blade.php
	new file:   resources/views/role-description/show.blade.php
	new file:   resources/views/role-has-permission/create.blade.php
	new file:   resources/views/role-has-permission/edit.blade.php
	new file:   resources/views/role-has-permission/index.blade.php
	new file:   resources/views/role-has-permission/print.blade.php
	new file:   resources/views/role-has-permission/show.blade.php
	new file:   resources/views/role/create.blade.php
	new file:   resources/views/role/edit.blade.php
	new file:   resources/views/role/index.blade.php
	new file:   resources/views/role/print.blade.php
	new file:   resources/views/role/show.blade.php
	modified:   routes/web.php
	modified:   webpack.mix.js
```

Invite - Change password, Forgot Password -  Most work was done between May 12 and May 27th 

## Add crud generator

In composer.json add:

```

composer require zmon/crud-generator-mysql

    "require-dev": {
        .
        .
        .
        .
        "phpunit/phpunit": "^8.5",
        "zmon/laravel-crud-generator-pgsql": "dev-master"
    },
    "repositories":[
        {
            "type": "vcs",
            "url": "git@github.com:zmon/crud-generator-mysql.git"
        }
    ],
```

Add to config/app.php the following line to the 'providers' array:

```
CrudGenerator\CrudGeneratorServiceProvider::class,
```

Create the following directories

```
mkdir -p Doc/CRUD app/Observers app/Lib/Import bin app/Exports
```

Should beable to do the following to create organizations

```
php artisan make:crud organizations  --display-name="organizations" --grid-columns="name"   --force
```

Follow the instructions in DOC/CRUD/Organization.md




The CRUD GENERATOR will make the needed changes to the Model.




parts:
* Mail - need to setup way to send mail
* Queue - add queues
* Vue Password *

## Excel

[Laravel Excel](https://docs.laravel-excel.com/3.1/getting-started/installation.html)
```
omposer require maatwebsite/excel
```

## Print PDF

[barryvdh / laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)

```
composer require barryvdh/laravel-dompdf
```



## Mail

You can use Google mail or mailtrap.io for testing

In .env

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

Or log mail to the loger

```
MAIL_DRIVER=log
```

## Queue

Setup Database for the Driver
See [Queue Driver Notes & Prerequisites](https://laravel.com/docs/5.8/queues#driver-prerequisites) for background information.

```
php artisan queue:table
php artisan migrate
```

Set only .env to use the database table as the Queue Connection.  

```
QUEUE_CONNECTION=database
```



# END
