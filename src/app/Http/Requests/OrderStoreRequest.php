<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class OrderStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data' => ['required', 'array'],
            'data.*.weight' => ['required', 'numeric'],
            'data.*.region' => ['required', 'integer'],
            'data.*.delivery_hours' => ['required', 'array'],
            'data.*.delivery_hours.*' => ['string'],
        ];
    }

    /**
     * Custom handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()
            ->json($validator->errors())
            ->setStatusCode(Response::HTTP_BAD_REQUEST));
    }
}
