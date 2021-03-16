<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SelfReportApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

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
        $rules = [
            'id' => 'numeric',
            '_exposed' => 'nullable|string|max:20',
            '_public_private_exposure' => 'nullable|string|max:60',
            '_state' => 'nullable|string|max:60',
            '_kscounty' => 'nullable|string|max:60',
            '_city_kcmo' => 'nullable|string|max:60',
            '_zipcode' => 'nullable|string|max:60',
            '_selfreport_or_other' => 'nullable|string|max:60',
            '_whose_symptoms' => 'nullable|string|max:60',
            '_sex' => 'nullable|string|max:60',
            '_age' => 'nullable|string|max:60',
            '_any_other_symptoms' => 'nullable|string|max:60',
            '_symptom_severity' => 'nullable|string|max:60',
            '_immunocompromised' => 'nullable|string|max:60',
            '_symptom_start_date' => 'nullable|string|max:60',
            '_followup_contact' => 'nullable|string|max:60',
            '_preferred_contact_method' => 'nullable|string|max:60',
            '_is_voicemail_ok' => 'nullable|string|max:60',
            '_crowded_setting' => 'nullable|string|max:60',
            '_anything_else' => 'nullable|string|max:60',
            '_FormVersionId' => 'nullable|numeric',
            '_FormId' => 'nullable|numeric',
            '_FormVersionNumber' => 'nullable|string|max:24',
            '_ResponseReferenceId' => 'nullable|string|max:36',
            '_ExternalId' => 'nullable|numeric',
            '_ExternalStatus' => 'nullable|string|max:20',
            '_ExternalRespondentId' => 'nullable|string|max:20',
            '_SourceType' => 'nullable|string|max:20',
            '_SourceElementId' => 'nullable|string|max:20',
            '_DataConnectionId' => 'nullable|string|max:20',
            '_CallCounter' => 'nullable|string|max:20',
        ];

        return $rules;
    }
}
