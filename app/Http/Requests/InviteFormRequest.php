<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InviteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('invite')) {  // If ID we must be changing an existing record
            return Auth::user()->can('invite edit');
        } else {  // If not we must be adding one
            return Auth::user()->can('invite add');
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route('invite');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',


            'id' => 'numeric',
            'email' => 'nullable|string',
            'role' => 'nullable|string',
            'expires_at' => 'nullable|string',
            'token' => 'nullable|string',
            'deleted_at' => 'nullable|string',

        ];

        $organization_id = session('organization_id', 0);

        if ($this->route('invite')) {  // If ID we must be changing an existing record
            $rules['name'] = 'required|string|max:60|unique:invites,name,' . $id . ',id,organization_id,' . $organization_id;
        } else {  // If not we must be adding one
            $rules['name'] = 'required|string|max:60|unique:invites,name,NULL,id,organization_id,' . $organization_id;
        }

        return $rules;
    }
}


