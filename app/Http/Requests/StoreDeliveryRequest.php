<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
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
            'provider_id'      => ['required', 'exists:providers,id'],
            'worker_id'        => ['required', 'exists:workers,id'],
            'receiver_name'    => ['required', 'string', 'max:255'],
            'receiver_address' => ['required', 'string', 'max:255'],
            'delivery_date'    => ['required', 'date'],
        ];
    }
}
