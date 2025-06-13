<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     title="Properties pagination links",
 *     @OA\Property(
 *         property="first",
 *         type="string",
 *         format="uri",
 *         example="http://localhost/?page=1",
 *     ),
 *     @OA\Property(
 *         property="last",
 *         type="string",
 *         format="uri",
 *         example="http://localhost/?page=10",
 *     ),
 *     @OA\Property(
 *         property="prev",
 *         type="string",
 *         format="uri",
 *         nullable=true,
 *         example="http://localhost/?page=1",
 *     ),
 *     @OA\Property(
 *          property="next",
 *          type="string",
 *          format="uri",
 *          nullable=true,
 *          example="http://localhost/?page=2",
 *      ),
 * )
 */
class PaginationLinks {}
