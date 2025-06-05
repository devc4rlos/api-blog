<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Blog",
 *     version="0.0.1",
 *     description="API to manage blog",
 *     @OA\Contact(
 *         name="Carlos Alexandre",
 *         email="dev@carlosalexandre.com.br",
 *         url="https://carlosalexandre.com.br",
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/license/mit",
 *     ),
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Main API server",
 * )
 */
abstract class Controller
{
    //
}
