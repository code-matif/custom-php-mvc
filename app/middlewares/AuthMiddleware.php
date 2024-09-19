<?php

namespace App\Middlewares;

class AuthMiddleware {
    public function handle($next) {
        return $next();
    }
}
