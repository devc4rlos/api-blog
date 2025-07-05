<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Post",
 *     title="Post",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string",
 *     ),
 *     @OA\Property(
 *         property="body",
 *         type="string",
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *      ),
 *     @OA\Property(
 *          property="status",
 *          type="string",
 *      ),
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
class Post {}
