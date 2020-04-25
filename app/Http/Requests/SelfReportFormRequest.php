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
            'organization_id' => 'nullable|numeric',
            'exposed' => 'nullable|string|max:20',
            'public_private_exposure' => 'nullable|string|max:60',
            'state' => 'nullable|string|max:60',
            'kscounty' => 'nullable|string|max:60',
            'mocounty' => 'nullable|string|max:60',
            'city_kcmo' => 'nullable|string|max:60',
            'zipcode' => 'nullable|string|max:60',
            'selfreport_or_other' => 'nullable|string|max:60',
            'whose_symptoms' => 'nullable|string|max:60',
            'sex' => 'nullable|string|max:60',
            'age' => 'nullable|string|max:60',
            'any_other_symptoms' => 'nullable|string|max:60',
            'symptom_severity' => 'nullable|string|max:60',
            'immunocompromised' => 'nullable|string|max:60',
            'symptom_start_date' => 'nullable|string|max:60',
            'followup_contact' => 'nullable|string|max:60',
            'preferred_contact_method' => 'nullable|string|max:60',
            'is_voicemail_ok' => 'nullable|string|max:60',
            'crowded_setting' => 'nullable|string|max:60',
            'anything_else' => 'nullable|string|max:60',
            'FormVersionId' => 'nullable|numeric',
            'FormId' => 'nullable|numeric',
            'FormVersionNumber' => 'nullable|string|max:24',
            'ExternalId' => 'nullable|numeric',
            'ExternalStatus' => 'nullable|string|max:20',
            'ExternalRespondentId' => 'nullable|string|max:20',
            'SourceType' => 'nullable|string|max:20',
            'SourceElementId' => 'nullable|string|max:20',
            'DataConnectionId' => 'nullable|string|max:20',
            'CallCounter' => 'nullable|string|max:20',
            'county_calc' => 'nullable|string|max:40',
            'form_received_at' => 'nullable|string',

        ];

                if ($this->route('self_report')) {  // If ID we must be changing an existing record
                    $rules['name'] = 'required|min:3|nullable|string|max:100|unique:self_reports,name,' . $id;
                } else {  // If not we must be adding one
                    $rules['name'] = 'required|min:3|nullable|string|max:100|unique:self_reports';
                }

        return $rules;
    }
}


