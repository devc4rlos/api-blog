<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class StandardPostController
{
    /**
     * @OA\Get(
     *     path="/v1/posts",
     *     summary="List Posts",
     *     description="Retrieves a paginated list of posts. Results can be filtered, sorted, and searched.",
     *     tags={"Public Posts"},
     *     @OA\Parameter(ref="#/components/parameters/pageFilter"),
     *     @OA\Parameter(ref="#/components/parameters/searchFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortByFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortDirectionFilter"),
     *     @OA\Response(
     *         response="200",
     *         description="List of posts retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Posts retrieved successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   type="array",
     *                   @OA\Items(ref="#/components/schemas/Post")
     *               ),
     *               @OA\Property(
     *                   property="links",
     *                   ref="#/components/schemas/PaginationLinks",
     *               ),
     *               @OA\Property(
     *                   property="meta",
     *                   ref="#/components/schemas/PaginationMeta",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/NotFoundRangePage"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function index(){}

    /**
     * @OA\Get(
     *     path="/v1/posts/{slug}",
     *     summary="Get a Specific Post",
     *     description="Retrieves the details of a specific post by its slug.",
     *     tags={"Public Posts"},
     *     @OA\Parameter(
     *         description="The slug of the post to retrieve.",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Post data retrieved successfully.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Post retrieved successfully."
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Post",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The post with the specified slug was not found.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The resource could not be found.",
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function show(){}

    /**
     * @OA\Get(
     *     path="/v1/posts/{slug}/comments",
     *     summary="List a Post's Comments",
     *     description="Retrieves a paginated list of comments for a specific post, identified by its slug.",
     *     tags={"Public Posts"},
     *     @OA\Parameter(
     *          description="The slug of the post whose comments are to be retrieved.",
     *          in="path",
     *          name="slug",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="List of the post's comments retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Comments for the post retrieved successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   type="array",
     *                   @OA\Items(ref="#/components/schemas/Comment")
     *               ),
     *               @OA\Property(
     *                   property="links",
     *                   ref="#/components/schemas/PaginationLinks",
     *               ),
     *               @OA\Property(
     *                   property="meta",
     *                   ref="#/components/schemas/PaginationMeta",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         ref="#/components/responses/NotFoundRangePage",
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function comments(){}
}
