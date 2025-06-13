<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     title="Properties pagination meta",
 *     @OA\Property(
 *         property="total",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="current_page",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *         property="last_page",
 *         type="integer",
 *     ),
 *     @OA\Property(
 *          property="path",
 *          type="string",
 *          format="uri",
 *          example="http://localhost",
 *      ),
 * )
 */
class PaginationMeta {}
