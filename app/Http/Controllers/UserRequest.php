<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        //       if ( ! $this->allowUserUpdate($this->user)){
//
        //          return false;
//
        //      }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name' => 'required|string|max:20|unique:users,name,'.$this->user,
            'email' => 'required|email|unique:users,email,'.$this->user,
        ];
    }
}
