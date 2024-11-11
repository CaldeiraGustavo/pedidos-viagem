<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 *
 * @OA\Schema(title="OrderResource", @OA\Xml(name="OrderResource"))
 */
class OrderResource extends JsonResource
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
     *     example="REQUESTED",
     *     enum={"REQUESTED", "APPROVED", "CANCELED"},
     *     default="REQUESTED"
     * )
     *
     * @var string
     */
    protected $status;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'destination' => $this->resource->destination,
            'departure_date' => Carbon::parse($this->resource->departure_date)->format('Y-m-d'),
            'return_date' => Carbon::parse($this->resource->return_date)->format('Y-m-d'),
            'status' => $this->resource->status,
        ];
    }
}
