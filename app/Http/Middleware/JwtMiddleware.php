<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Config;

class JwtMiddleware
{
    public function __construct(
        private JWT $jwt,
        private ApiToken $apiToken)
    {
        $this->jwt = $jwt;
        $this->apiToken = $apiToken;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization') || is_null($request->header('Authorization'))) {
            return response()->json(
                ['message' => 'unauthorized access api - report apikey'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $jwt = $this->jwt->decode(str_replace('Bearer ', '', $bearer), Config::get('app.key'), ['HS256']);

            $credential = $this->apiToken->where('credential', $jwt->credential)->first();

            if (!$credential->status) {
                return response()->json(['message' => 'inactive apikey'], Response::HTTP_UNAUTHORIZED);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'invalid apikey - report apikey valid'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
