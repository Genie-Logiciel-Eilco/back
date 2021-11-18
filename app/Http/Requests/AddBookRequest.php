<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBookRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'isbn'=>'required',
            'name'=>'required',
            'synopsis'=>'required',
            "authors"=>"required|array",
            "categories"=>"required|array",
            "categories.*"=>"int|distinct",
            "authors.*"=>"int|distinct",
            "publisher_id"=>"int"
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}