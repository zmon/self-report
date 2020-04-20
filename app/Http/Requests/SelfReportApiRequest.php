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
        ];
        return $rules;
    }
}


