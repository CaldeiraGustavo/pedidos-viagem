<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Requests\{
    OrderStoreRequest,
    OrderUpdateRequest
};

class OrderController extends Controller
{
    public function __construct(private OrderRepositoryInterface $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        $this->repository->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->repository->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($order, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(OrderUpdateRequest $request, string $id)
    {
        $this->repository->updateById($id, $request->validated());
    }
}
