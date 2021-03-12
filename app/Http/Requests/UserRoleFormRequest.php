<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRoleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('user_role')) {  // If ID we must be changing an existing record
            return Auth::user()->can('user_role edit');
        } else {  // If not we must be adding one
            return Auth::user()->can('user_role add');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user_role');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',

            'id' => 'numeric',
            'organization_id' => 'nullable|numeric',
            'type' => 'nullable|string',
            'alias' => 'nullable|string',
            'sequence' => 'nullable|numeric',
            'menu_id' => 'nullable|numeric',

        ];

        if ($id) {
            $rules['name'] = 'required|string|max:60|unique:user_roles,name,'.$id.',id';
        } else {
            $rules['name'] = 'required|string|max:60|unique:user_roles,name,NULL,id';
        }

        return $rules;
    }
}
