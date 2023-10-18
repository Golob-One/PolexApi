<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParrainageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            "prenom"=>"required|min:3|max:50",
            "nom"=>"required|min:2|max:20",
            "nin"=>"required|digits_between: 13,14",
            "num_electeur"=>"required|digits_between: 9,9",
            "date_expir"=>"required",
            "region"=>"required|string",
        ];
    }
}
