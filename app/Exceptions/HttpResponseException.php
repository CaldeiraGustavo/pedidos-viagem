<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpResponseException
 *
 * @OA\Schema(title="HttpResponseException", @OA\Xml(name="HttpResponseException"))
 */
class HttpResponseException extends RuntimeException
{
    /**
     * @OA\Property(format="string",
     *     description="Main error message",
     *     title="message",
     *     example="Error message"
     * )
     *
     * @var string
     */
    protected $message;

    /**
     * @OA\Property(
     *    property="errors",
     *    type="object",
     *    @OA\Property(
     *      property="error_key",
     *      type="array",
     *      @OA\Items(type="string", example="Error message refering to error_key")
     *    )
     *  )
     * @var string
     */
    protected $errors;

    /**
     * The underlying response instance.
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * Create a new HTTP response exception instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
