<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderStatus;

class OrderStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'destination' => ['required', 'string', 'max:100'],
            'departure' => ['required', 'date', 'date_format:Y-m-d', 'before:return'],
            'return' => ['required', 'date', 'date_format:Y-m-d', 'after:departure'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];
    }
}
