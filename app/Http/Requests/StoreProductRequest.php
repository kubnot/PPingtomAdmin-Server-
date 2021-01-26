<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name'         => [
                'string',
                'required',
            ],
            'description'  => [
                'required',
            ],
            'subcategories.*' => [
                'integer',
            ],
            'subcategories'   => [
                'array',
            ],
            'tags.*'       => [
                'integer',
            ],
            'tags'         => [
                'array',
            ],
            'city'         => [
                'string',
                'required',
            ],
            'country'      => [
                'string',
                'required',
            ],
            'latitdue'     => [
                'numeric',
                'required',
            ],
            'longitude'    => [
                'numeric',
                'required',
            ],
        ];
    }
}
