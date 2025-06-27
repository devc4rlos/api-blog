<?php

namespace App\OpenApi\Responses;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="Forbidden",
 *     description="Access Denied. The authenticated user is not authorized to access this resource or perform this action.",
 *     @OA\JsonContent(
 *         type="object",
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="You don't have permission to perform this action.",
 *         ),
 *     ),
 * ),
 */
class Forbidden {}
