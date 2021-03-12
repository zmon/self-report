<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RaceEthnicityFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('race_ethnicity')) {  // If ID we must be changing an existing record
            return Auth::user()->can('race_ethnicity update');
        } else {  // If not we must be adding one
            return Auth::user()->can('race_ethnicity add');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('race_ethnicity');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',

            'id' => 'numeric',

        ];

        if ($this->route('race_ethnicity')) {  // If ID we must be changing an existing record
            $rules['name'] = 'required|min:3|nullable|string|max:100|unique:race_ethnicities,name,'.$id;
        } else {  // If not we must be adding one
            $rules['name'] = 'required|min:3|nullable|string|max:100|unique:race_ethnicities';
        }

        return $rules;
    }
}
