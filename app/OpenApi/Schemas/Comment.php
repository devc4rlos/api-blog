<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Comment",
 *     title="Comment",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="body",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="post_id",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
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
class Comment {}
