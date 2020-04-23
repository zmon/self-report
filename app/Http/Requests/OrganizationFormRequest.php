<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrganizationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('organization')) {  // If ID we must be changing an existing record
            return Auth::user()->can('organization update');
        } else {  // If not we must be adding one
            return Auth::user()->can('organization add');
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route('organization');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',


            'id' => 'numeric',
            'alias' => 'nullable|string|max:16',

        ];

        if ($this->route('organization')) {  // If ID we must be changing an existing record
            $rules['name'] = 'required|min:3|nullable|string|max:60|unique:organizations,name,' . $id;
        } else {  // If not we must be adding one
            $rules['name'] = 'required|min:3|nullable|string|max:60|unique:organizations';
        }

        return $rules;
    }
}


