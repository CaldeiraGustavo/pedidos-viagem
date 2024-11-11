<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderStatus;

/**
 * Class OrderUpdateRequest
 *
 * @OA\Schema(title="OrderUpdateRequest", required={"interesses"}, @OA\Xml(name="OrderUpdateRequest"))
 */
class OrderUpdateRequest extends FormRequest
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="Request Status",
     *     title="status",
     *     example="Approved",
     *     enum={"Requested", "Approved", "Canceled"},
     *     default="Approved"
     * )
     *
     * @var string
     */
    protected $status;

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
            'status' => [
                'required',
                Rule::enum(OrderStatus::class)
                ->only([OrderStatus::APPROVED, OrderStatus::CANCELED])
            ],
        ];
    }
}
