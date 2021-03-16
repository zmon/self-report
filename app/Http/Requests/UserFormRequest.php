<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('user')) {  // If ID we must be changing an existing record
            return Auth::user()->can('user update');
        } else {  // If not we must be adding one
            return Auth::user()->can('user add');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user');

        $rules = [
         //  Ignore duplicate email if it is this record
         //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',

            'id' => 'numeric',
            'organization_id' => 'required|numeric|exists_or_null:organizations,id',
            'email' => 'nullable|string|max:255',
            'active' => 'nullable|numeric',
            'email_verified_at' => 'nullable|string',
            'password' => 'nullable|string|max:255',
            'remember_token' => 'nullable|string|max:100',

        ];

        if ($this->route('user')) {  // If ID we must be changing an existing record
            $rules['name'] = 'required|min:3|nullable|string|max:255|unique:users,name,'.$id;
        } else {  // If not we must be adding one
            $rules['name'] = 'required|min:3|nullable|string|max:255|unique:users';
        }

        return $rules;
    }
}
