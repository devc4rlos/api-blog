<?php

namespace App\OpenApi\Responses;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="NotFoundRangePage",
 *     description="Requested page is out of range",
 *     @OA\JsonContent(
 *         type="object",
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Requested page (5) is out of range. The last page is 2.",
 *         ),
 *     ),
 * ),
 */
class NotFoundRangePage {}
