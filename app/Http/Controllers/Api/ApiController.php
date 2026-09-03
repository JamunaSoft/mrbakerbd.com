<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Mr. Baker API Documentation",
 *     description="API documentation for Mr. Baker e-commerce platform",
 *     @OA\Contact(
 *         email="admin@mrbaker.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiController extends \App\Http\Controllers\Controller
{
    use ApiResponse;
}
