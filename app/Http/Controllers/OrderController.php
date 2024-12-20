<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Requests\{
    OrderStoreRequest,
    OrderUpdateRequest,
    OrderShowRequest
};
use App\Http\Resources\{
    OrderResource,
    PaginatedOrderResource
};
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private OrderRepositoryInterface $repository)
    {

    }

    /**
     * @OA\Get(
     *     path="/orders",
     *     description="List all orders",
     *     description="List all orders",
     *     security={{ "api_token": {} }},
     *     tags={"Order"},
     *     @OA\Parameter(
     *        description="Page",
     *        in="query",
     *        name="page",
     *        example="1",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *     ),
     *     @OA\Parameter(
     *        description="Limit records per page",
     *        in="query",
     *        name="limit",
     *        example="9",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Retorna notícias da agencia sebrae",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/PaginatedOrderResource"))),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     * )
     */
    public function index(OrderShowRequest $request)
    {
        try {
            $status = $request->get('status') ?? null;
            $limit = $request->get('limit') ?? 15;

            $orders = $status ? $this->repository->where('status', $status)->paginate($limit) : $this->repository->paginate($limit);

            return response()->json(new PaginatedOrderResource($orders), Response::HTTP_OK);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(
                ['message' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *      path="/orders",
     *      tags={"Order"},
     *      summary="Create order",
     *      description="Create order",
     *      security={{ "api_token": {} }},
     *      @OA\RequestBody(
     *          description="Requested data to create a order",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderStoreRequest")),
     *      @OA\Response(
     *           response=201,
     *           description="Saved successfully",
     *           @OA\JsonContent(
     *               @OA\Examples(
     *                   example="message",
     *                   value={"message": "Saved successfully"},
     *                   summary="Success message"))),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     *      @OA\Response(
     *           response=422,
     *           description="Invalid data.",
     *           @OA\JsonContent(ref="#/components/schemas/HttpResponseException")),
     *      @OA\Response(
     *           response=500,
     *           description="Internal server error",
     *           @OA\JsonContent(
     *               @OA\Examples(
     *                   example="result",
     *                   value={"message": "Example error message"},
     *                   summary="Exception error message"))),
     * ),
     */
    public function store(OrderStoreRequest $request)
    {
        try {
            $this->repository->create($request->validated());

            return response()->json(
                ['message' => 'Saved successfully.'],
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(
                ['message' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     description="Return all data from a order by ID",
     *     description="Return all data from a order by ID",
     *     security={{ "api_token": {} }},
     *     tags={"Order"},
     *     @OA\Parameter(
     *        description="id",
     *        in="path",
     *        name="id",
     *        example="1",
     *        required=true,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Return all data from a order",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResource")),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     *     @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Order not found"},
     *                  summary="Error message"))),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     * )
     */
    public function show(string $id)
    {
        try {
            $order = $this->repository->find($id);

            if (!$order) {
                return response()->json(
                    ['message' => 'Order not found'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->json(new OrderResource($order), Response::HTTP_OK);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(
                ['message' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Patch(
     *     path="/orders/{id}/status",
     *     tags={"Order"},
     *     summary="Update order status",
     *     description="Update order status",
     *     security={{ "api_token": {} }},
     *     @OA\Parameter(
     *        description="id",
     *        in="path",
     *        name="id",
     *        example="1",
     *        required=true,
     *     ),
     *     @OA\RequestBody(
     *          description="Needed data to update order status",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderUpdateRequest")),
     *     @OA\Response(
     *          response=200,
     *          description="Status updated successfully.",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResource")),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     *     @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Order not found"},
     *                  summary="Error message"))),
     *     @OA\Response(
     *          response=422,
     *          description="Invalid data.",
     *          @OA\JsonContent(ref="#/components/schemas/HttpResponseException")),
     *     @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     * ),
     */
    public function updateStatus(OrderUpdateRequest $request, string $id)
    {
        try {
            $order = $this->repository->find($id);

            if (!$order) {
                return response()->json(
                    ['message' => 'Order not found'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $order->update($request->validated());

            return response()->json(new OrderResource($order), Response::HTTP_OK);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(
                ['message' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
