<?php
namespace Backend\Products\Middleware;

use Backend\Products\JWT\TokenManager;
use Closure;

class TokenMiddleware
{

    private $tokenValidator;

    public function __construct()
    {
        $this->tokenValidator = new TokenManager();
    }
    public function handle(Closure $next)
    {
        $validateToken = $this->tokenValidator->verifyToken();
        if (!!$validateToken) {
            var_dump($validateToken);
            return call_user_func($next);
        } else {
            return json_encode([
                "msg" => "Usuário não autorizado"
            ]);
        }
    }
}