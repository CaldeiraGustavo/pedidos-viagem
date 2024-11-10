<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Requests\{
    OrderStoreRequest,
    OrderUpdateRequest,
    OrderShowRequest
};
use App\Http\Resources\PaginatedOrderResource;

class OrderController extends Controller
{
    public function __construct(private OrderRepositoryInterface $repository)
    {

    }

    /**
     * @OA\Get(
     *     path="/order",
     *     description="Lista todas as notícias vindas da agencia sebrae de notícias.",
     *     description="Lista todas as notícias vindas da agencia sebrae de notícias.",
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
     *        description="Quantidade máxima de registros por página",
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
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     * )
     */
    public function index(OrderShowRequest $request)
    {
        $status = $request->get('status') ?? null;
        $limit = $request->get('limit') ?? 15;

        $orders = $status ? $this->repository->where('status', $status)->paginate($limit) : $this->repository->paginate($limit);

        return response()->json(new PaginatedOrderResource($orders), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/order",
     *      tags={"Order"},
     *      summary="Cadastra alguém no formulário do programa",
     *      description="Cadastra alguém no formulário",
     *      security={{ "apikey": {} }},
     *      @OA\RequestBody(
     *          description="Dados necessários para validação de cadastro no formulário",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderStoreRequest")),
     *      @OA\Response(
     *           response=201,
     *           description="Formulário cadastrado com sucesso.",
     *           @OA\JsonContent(
     *               @OA\Examples(
     *                   example="message",
     *                   value={"message": "Cadastrado com sucesso."},
     *                   summary="Mensagem de sucesso"))),
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
     *           description="Entrada de dados inválida.",
     *           @OA\JsonContent(ref="#/components/schemas/HttpResponseException")),
     *      @OA\Response(
     *           response=500,
     *           description="Erro interno do servidor.",
     *           @OA\JsonContent(
     *               @OA\Examples(
     *                   example="result",
     *                   value={"message": "Example error message"},
     *                   summary="Exception error message"))),
     * ),
     */
    public function store(OrderStoreRequest $request)
    {
        $this->repository->create($request->validated());
    }

    public function show(string $id)
    {
        $order = $this->repository->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($order, Response::HTTP_CREATED);
    }

    /**
     * @OA\Patch(
     *     path="/order",
     *     tags={"Order"},
     *     summary="Atualiza o vendedor",
     *     description="Atualiza o vendedor",
     *     security={{ "amei_key": {}, "api_token": {} }},
     *     @OA\RequestBody(
     *          description="Dados necessários para validação de atualização de vendedor",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderUpdateRequest")),
     *     @OA\Response(
     *          response=200,
     *          description="Vendedor atualizado com sucesso.",
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
     *          response=422,
     *          description="Entrada de dados inválida.",
     *          @OA\JsonContent(ref="#/components/schemas/HttpResponseException")),
     *     @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "Example error message"},
     *                  summary="Exception error message"))),
     * ),
     */
    public function updateStatus(OrderUpdateRequest $request, string $id)
    {
        $this->repository->updateById($id, $request->validated());
    }
}
