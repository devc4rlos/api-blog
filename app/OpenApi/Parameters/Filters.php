<?php

namespace App\OpenApi\Parameters;

use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *      description="Search filter",
 *      parameter="searchFilter",
 *      in="query",
 *      name="search",
 *      required=false,
 *      @OA\Schema(type="string"),
 *  ),
 * @OA\Parameter(
 *     description="Sort by filter",
 *     parameter="sortByFilter",
 *     in="query",
 *     name="sortBy",
 *     required=false,
 *     @OA\Schema(type="string"),
 * ),
 * @OA\Parameter(
 *     description="Sort direction filter",
 *     parameter="sortDirectionFilter",
 *     in="query",
 *     name="sortDirection",
 *     required=false,
 *     @OA\Schema(type="string"),
 * ),
 * @OA\Parameter(
 *     description="Relationships filter",
 *     parameter="relationshipsFilter",
 *     in="query",
 *     name="relationships",
 *     required=false,
 *     @OA\Schema(type="string"),
 * ),
 * @OA\Parameter(
 *     description="Page filter",
 *     parameter="pageFilter",
 *     in="query",
 *     name="page",
 *     required=false,
 *     @OA\Schema(type="string"),
 * ),
 */
class Filters {}
