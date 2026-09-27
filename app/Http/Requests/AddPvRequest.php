<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPvRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'u' => 'required|string|max:255|url',
            't' => 'required|string|max:255',
            'i' => 'nullable|numeric|max:4294967295'
        ];
    }
}