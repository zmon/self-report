<?php

namespace App\Http\Controllers\Auth;

use ZxcvbnPhp\Zxcvbn;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = env('PASSWORD_RESET_REDIRECT', '/login');
        $this->middleware('guest');

    }


    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {

        return [
            'token' => 'required',
            'email' => 'required|email|exists:users,email,active,1',
            'password' => [
                'required',
                'confirmed',
                function ($attribute, $value, $fail) {

                    $zxcvbn = new Zxcvbn();

                    $user_inputs = [];

                    $users_email = request()->email;

                    if ($users_email) {
                        $user_inputs[] = $users_email;
                    }

                    $strength = $zxcvbn->passwordStrength($value, $user_inputs);

                    if (intval($strength['score']) < 3) {
                        $fail($attribute . ' is to weak.');
                    } else {
                        return true;
                    }
                },
            ],
        ];
    }


}
