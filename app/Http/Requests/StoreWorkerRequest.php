<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rules\Password;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:59'],
            'email' => ['required', 'email', 'max:50', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'license_number' => ['required', 'string', 'max:20'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'completed_deliveries' => ['required', 'integer'],
        ];
    }
}
