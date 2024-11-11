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
     *     description="Departure date",
     *     title="departure_date",
     *     example="2023-02-13"
     * )
     *
     * @var string
     */
    protected $departure_date;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Return date",
     *     title="return_date",
     *     example="2023-02-14"
     * )
     *
     * @var string
     */
    protected $return_date;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Request Status",
     *     title="status",
     *     example="Requested",
     *     enum={"Requested", "Approved", "Canceled"},
     *     default="Requested"
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
            'departure_date' => ['required', 'date', 'date_format:Y-m-d', 'before:return_date'],
            'return_date' => ['required', 'date', 'date_format:Y-m-d', 'after:departure_date'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];
    }
}
