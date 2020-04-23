<?php

// From https://laracasts.com/discuss/channels/code-review/api-authentication-with-passport
//   resolves issue of SPA having to send a secret
// General http://esbenp.github.io/2017/03/19/modern-rest-api-laravel-part-4/

namespace App\Http\Middleware;

use DB;
use Closure;
use Illuminate\Http\Request;

class InjectPasswordGrantCredentials
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        $password_client_id = data_get(config(), 'passport.password_client_id', false);

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
