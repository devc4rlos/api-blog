<?php

namespace App\OpenApi\Responses;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="TooManyRequests",
 *     description="Too Many Requests. The user has sent too many requests in a given amount of time.",
 *     @OA\Header(
 *         header="X-RateLimit-Limit",
 *         description="The maximum number of requests allowed in the current time window.",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Header(
 *         header="X-RateLimit-Remaining",
 *         description="The number of requests remaining in the current time window.",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     )
 * )
 */
class TooManyRequestsResponse {}
