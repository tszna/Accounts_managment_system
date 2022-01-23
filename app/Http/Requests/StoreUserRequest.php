<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreUserRequest extends FormRequest
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
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                $validator->errors()->all(),
                JsonResponse::HTTP_BAD_REQUEST
            )
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'email' => 'required|email|unique:App\Models\User\User,email',
            'password' => 'required|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?& ]{8,255}$/',
            'password_repeat' => 'required|same:password',
            'professor' => 'required_without:administration_employee',
            'professor.phone' => 'required_without:administration_employee|unique:App\Models\Professor\Professor,phone|regex:/^([+]){0,1}[\d ]{8,}$/',
            'professor.level_of_education' => 'required_without:administration_employee|min:3',
            'administration_employee' => 'required_without:professor',
            'administration_employee.correspondence_address' => 'required_without:professor|array',
            'administration_employee.correspondence_address.voivodeship' => 'required_without:professor|min:3',
            'administration_employee.correspondence_address.city' => 'required_without:professor|min:3',
            'administration_employee.correspondence_address.postal_code' => 'required_without:professor|regex:/^\d{2}-\d{3}$/',
            'administration_employee.correspondence_address.street' => 'required_without:professor|min:3',
            'administration_employee.correspondence_address.number' => 'required_without:professor|min:1',
            'administration_employee.home_address' => 'required_without:professor|array',
            'administration_employee.home_address.voivodeship' => 'required_without:professor|min:3',
            'administration_employee.home_address.city' => 'required_without:professor|min:3',
            'administration_employee.home_address.postal_code' => 'required_without:professor|regex:/^\d{2}-\d{3}$/',
            'administration_employee.home_address.street' => 'required_without:professor|min:3',
            'administration_employee.home_address.number' => 'required_without:professor|min:1',
        ];
    }
}
