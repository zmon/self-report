<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SelfReportFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('self_report')) {  // If ID we must be changing an existing record
            return Auth::user()->can('self_report update');
        } else {  // If not we must be adding one
            return Auth::user()->can('self_report add');
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $id = $this->route('self_report');

        $rules = [
         //  Ignore duplicate email if it is this record
         //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',


            'id' => 'numeric',

        ];

                if ($this->route('self_report')) {  // If ID we must be changing an existing record
                    $rules['name'] = 'required|min:3|nullable|string|max:60|unique:self_reports,name,' . $id;
                } else {  // If not we must be adding one
                    $rules['name'] = 'required|min:3|nullable|string|max:60|unique:self_reports';
                }

        return $rules;
    }
}


