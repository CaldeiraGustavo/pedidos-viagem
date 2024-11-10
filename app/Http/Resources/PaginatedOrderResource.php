<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PaginatedOrderResource
 *
 * @OA\Schema(title="PaginatedOrderResource", @OA\Xml(name="PaginatedOrderResource"))
 */
class PaginatedOrderResource extends JsonResource
{
    /**
     * @OA\Property(
     *     format="integer",
     *     description="Página atual",
     *     title="currentPage",
     *     example=1
     * )
     *
     */
    protected $currentPage;

    /**
     * @OA\Property(
     *     property="data",
     *     type="array",
     *     @OA\Items(
     *         ref="#/components/schemas/OrderResource"
     *     )
     * )
     *
     * @var string
     */
    protected $data;

    /**
     * @OA\Property(
     *     format="integer",
     *     description="Ultima página",
     *     title="lastPage",
     *     example=10
     * )
     *
     */
    protected $lastPage;

    /**
     * @OA\Property(
     *     format="integer",
     *     description="Total de páginas",
     *     title="total",
     *     example=15
     * )
     *
     */
    protected $total;

    /**
     * @OA\Property(
     *     format="integer",
     *     description="Ultima página",
     *     title="perPage",
     *     example=10
     * )
     *
     */
    protected $perPage;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "currentPage" => $this->resource->currentPage(),
            "lastPage" => $this->resource->lastPage(),
            "perPage" => $this->resource->perPage(),
            "total" => $this->resource->total(),
            "data" => OrderResource::collection($this->resource->items()),
        ];
    }
}
