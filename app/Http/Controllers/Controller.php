<?php

namespace App\Http\Controllers;

/**
 * @OA\Swagger(
 *     schemes={"http", "https"},
 *
 *     @OA\Info(
 *          version="1.0.0",
 *          title="Documentação Pedidos de viagens",
 *          description="Swagger Pedidos de viagens",
 *
 *          @OA\Contact(
 *              email="gustavo.caldeira1@outlook.com"
 *          ),
 *
 *          @OA\License(
 *              name="Apache 2.0",
 *              url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *          )
 *     )
 * ),
 *
 * @OA\SecurityScheme(
 *   securityScheme="apikey",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization",
 *   description="Please enter the authorization token.",
 * ),
 *
 * @OA\Tag(
 *     name="Order",
 *     description="Endpoints to control travel orders"
 * )
 */
abstract class Controller
{
    //
}
