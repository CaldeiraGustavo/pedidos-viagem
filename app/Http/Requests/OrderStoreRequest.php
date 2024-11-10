<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderStatus;

/**
 * Class OrderStoreRequest
 *
 * @OA\Schema(title="OrderStoreRequest", required={"interesses"}, @OA\Xml(name="OrderStoreRequest"))
 */
class OrderStoreRequest extends FormRequest
{
    /**
     * @OA\Property(
     *     format="string",
     *     description="User name",
     *     title="name",
     *     example="João de Souza"
     * )
     *
     * @var string
     */
    protected $name;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User destination",
     *     title="destination",
     *     example="Santo Antônio do Itambé"
     * )
     *
     * @var string
     */
    protected $destination;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Telefone",
     *     title="departure",
     *     example="31 3625-3355"
     * )
     *
     * @var string
     */
    protected $departure;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Cidade",
     *     title="cidade",
     *     example="São Joaquim de Bicas"
     * )
     *
     * @var string
     */
    protected $return;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Request Status",
     *     title="status",
     *     example="REQUESTED",
     *     enum={"REQUESTED", "APPROVED", "CANCELED"},
     *     default="REQUESTED"
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
            'name' => ['required', 'string', 'max:50'],
            'destination' => ['required', 'string', 'max:100'],
            'departure' => ['required', 'date', 'date_format:Y-m-d', 'before:return'],
            'return' => ['required', 'date', 'date_format:Y-m-d', 'after:departure'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];
    }
}
