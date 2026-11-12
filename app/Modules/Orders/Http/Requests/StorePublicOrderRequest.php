<?php

namespace App\Modules\Orders\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Orders\Support\ColombiaDepartments;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StorePublicOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->normalizePhone($this->input('phone')),
            'id_number' => $this->digitsOnly($this->input('id_number')),
        ]);
    }

    private function normalizePhone(mixed $value): mixed
    {
        $digits = $this->digitsOnly($value);

        if (is_string($digits) && strlen($digits) === 12 && str_starts_with($digits, '57')) {
            return substr($digits, 2);
        }

        return $digits;
    }

    private function digitsOnly(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }
    
        return preg_replace('/[\s.\-()+]/', '', $value);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s\'.\-]+$/u'],
            'id_number' => ['required', 'string', 'regex:/^\d{6,10}$/'],
            'phone' => ['required', 'string', 'regex:/^3\d{9}$/'],
            'email' => ['required', 'email', 'max:120'],
            'department' => ['required', 'string', Rule::in(ColombiaDepartments::LIST)],
            'city' => ['required', 'string', 'min:2', 'max:80'],
            'neighborhood' => ['nullable', 'string', 'max:80'],
            'address' => ['required', 'string', 'min:5', 'max:200'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string', 'max:500'],
            // La genera la landing (partial order-form-protection). Si falta,
            // OrderService crea una, pero entonces no hay protección de doble envío.
            'idempotency_key' => ['nullable', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return         
        [
            'full_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'id_number.regex' => 'La cédula debe tener entre 6 y 10 dígitos, sin letras.',
            'phone.regex' => 'El celular debe ser un número colombiano de 10 dígitos que empiece por 3.',
            'department.in' => 'Selecciona un departamento de la lista.',
        ];
    }
}
