<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaNumCustom;

class ProductsReviewRequest extends FormRequest
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
        // comp_noのバリデーション
        $comp_no_validate = [
            'nullable',
            'string',
            new AlphaNumCustom(null, 10)
        ];

        // products_noのバリデーション
        $products_no_validate = [
            'required',
            'string',
            new AlphaNumCustom(null, 10)
        ];

        return [
            'n' => $products_no_validate,
            'c' => $comp_no_validate,
            'start' => 'required|numeric'
        ];
    }
}