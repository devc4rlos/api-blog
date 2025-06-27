<?php

namespace App\OpenApi\Responses;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="Unauthenticated",
 *     description="Unauthenticated. Access token missing, invalid, or expired.",
 *     @OA\JsonContent(
 *         type="object",
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Unauthenticated.",
 *         ),
 *     ),
 * ),
 */
class Unauthenticated {}
