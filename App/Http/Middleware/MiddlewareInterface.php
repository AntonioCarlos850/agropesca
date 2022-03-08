<?php
namespace App\Http\Middleware;

use App\Http\Response;

interface MiddlewareInterface
{
    public function handle(array $request, $next);
}