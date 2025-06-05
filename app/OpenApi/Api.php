<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/",
 *     @OA\Get(
 *         summary="Check API status",
 *         @OA\Response(
 *             response="200",
 *             description="API status",
 *             @OA\JsonContent(
 *                 @OA\Property(property="status", type="boolean"),
 *             )
 *         )
 *     )
 * )
 */
class Api {}
