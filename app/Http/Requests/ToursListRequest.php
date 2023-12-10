<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\Cast\Array_;

class ToursListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'numeric',
            'priceTo'   => 'numeric',
            'dateFrom'  => 'date',
            'dateTo'    => 'date',
            'sortBy'    => Rule::in(['price']),
            'sortOrder'    => Rule::in(['ASC', 'DESC']),
        ];
    }

    public function messages() : Array{
        return [
            'sortBy' => "The 'sortBy' parameter accept only 'price' value",
            'sortOrder' => "The 'sortOrder' parameter Accept Only 'ASC' or 'DESC' values"
        ];
    }

}
