<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StandardUser",
 *     title="Standard User",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="datetime",
 *     ),
 *     @OA\Property(
 *         property="update_at",
 *         type="datetime",
 *     ),
 * )
 */
class StandardUser {}
