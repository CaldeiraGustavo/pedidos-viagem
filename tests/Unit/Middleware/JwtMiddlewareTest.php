<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\JwtMiddleware;
use App\Models\ApiToken;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Mockery;
use stdClass;
use Tests\TestCase;

class JwtMiddlewareTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = Request::create('/admin', 'GET');
    }

    public function testShouldReturnUnauthorized()
    {
        $jwt = $this->instance(
            Jwt::class,
            Mockery::mock(Jwt::class, function ($mock) {
                $aux = new stdClass();
                $aux->credential = true;
                $mock->shouldReceive('decode')->andReturn($aux);
            })
        );

        $apiToken = $this->instance(
            ApiToken::class,
            Mockery::mock(ApiToken::class, function ($mock) {
                $aux = new stdClass();
                $aux->status = true;

                $mock->shouldReceive('where->first')->andReturn($aux);
            })
        );

        $middleware = new JwtMiddleware($jwt, $apiToken);

        $response = $middleware->handle($this->request, function () {
            return true;
        });

        $this->assertEquals($response->getStatusCode(), 401);
    }

    public function testShouldReturnNoContentAuthNull()
    {
        $this->request->headers->set('Authorization', null);

        $jwt = $this->instance(
            Jwt::class,
            Mockery::mock(Jwt::class, function ($mock) {
                $aux = new stdClass();
                $aux->credential = true;
                $mock->shouldReceive('decode')->andReturn($aux);
            })
        );

        $apiToken = $this->instance(
            ApiToken::class,
            Mockery::mock(ApiToken::class, function ($mock) {
                $aux = new stdClass();
                $aux->status = 'ACTIVE';

                $mock->shouldReceive('where->first')->andReturn($aux);
            })
        );

        $middleware = new JwtMiddleware($jwt, $apiToken);
        $response = $middleware->handle($this->request, function () {
            return true;
        });

        $this->assertEquals($response->getStatusCode(), 401);
    }

    public function testShouldReturnInvalidApiKey()
    {
        $this->request->headers->set('Authorization', 'Bearer tokens');

        $jwt = $this->instance(
            Jwt::class,
            Mockery::mock(Jwt::class, function ($mock) {
                $aux = new stdClass();
                $aux->credential = true;
                $mock->shouldReceive('decode')->andReturn($aux);
            })
        );

        $apiToken = $this->instance(
            ApiToken::class,
            Mockery::mock(ApiToken::class, function ($mock) {
                $aux = new stdClass();
                $aux->status = false;

                $mock->shouldReceive('where->first')->andReturn($aux);
            })
        );

        $middleware = new JwtMiddleware($jwt, $apiToken);
        $response = $middleware->handle($this->request, function () {
            return true;
        });

        $this->assertEquals($response->getStatusCode(), 401);
        $this->assertEquals(
            json_decode($response->getContent()),
            (object) ['message' => 'invalid apikey - report apikey valid']
        );
    }
}
