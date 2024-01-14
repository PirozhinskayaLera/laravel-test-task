<?php

namespace App\Http\Requests;

use App\Models\Courier;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CourierStoreRequest extends FormRequest
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
            'data.*.courier_type' => ['required', Rule::in(Courier::$courierTypes), 'string'],
            'data.*.regions' => ['required', 'array'],
            'data.*.regions.*' => ['integer'],
            'data.*.working_hours' => ['required', 'array'],
            'data.*.working_hours.*' => ['string'],
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
