<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PasswordResetFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('password_reset')) {  // If ID we must be changing an existing record
            return Auth::user()->can('password_reset edit');
        } else {  // If not we must be adding one
            return Auth::user()->can('password_reset add');
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route('password_reset');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',


            'email' => 'nullable|string',
            'token' => 'nullable|string',

        ];


        return $rules;
    }
}


