<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class OrderCompleteRequest extends FormRequest
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
            'courier_id' => ['required', 'integer', 'exists:couriers,id'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'complete_time' => ['required', 'date_format:Y-m-d\\TH:i:s.vp']
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
