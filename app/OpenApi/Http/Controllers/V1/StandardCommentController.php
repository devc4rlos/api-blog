<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class StandardCommentController
{
    /**
     * @OA\Get(
     *     path="/v1/comments",
     *     summary="List My Comments",
     *     description="Retrieves a paginated list of all comments created by the authenticated user",
     *     tags={"My comments"},
     *     @OA\Parameter(ref="#/components/parameters/pageFilter"),
     *     @OA\Parameter(ref="#/components/parameters/searchFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortByFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortDirectionFilter"),
     *     @OA\Parameter(ref="#/components/parameters/relationshipsFilter"),
     *     @OA\Response(
     *         response="200",
     *         description="List of your comments retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Your comments were retrieved successfully."
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
     *         ref="#/components/responses/NotFoundRangePage"
     *     ),
     *     @OA\Response(
     *           response="401",
     *           ref="#/components/responses/Unauthenticated"
     *       ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/v1/comments",
     *     summary="Add a Comment to a Post",
     *     description="Creates a new comment on a post. The author will automatically be set as the authenticated user.",
     *     tags={"My comments"},
     *     @OA\RequestBody(
     *          description="The comment's content and the post's ID.",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"body", "post_id"},
     *              @OA\Property(
     *                  property="body",
     *                  type="string",
     *                  description="The content/text of the comment.",
     *                  example="This is a great post!"
     *              ),
     *              @OA\Property(
     *                  property="post_id",
     *                  type="string",
     *                  description="ID of the post the comment belongs to.",
     *                  example="9bde896a-3a7c-42c2-92d6-1b334a9c372a"
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Comment added successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Comment created successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Comment",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="The given data was invalid.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="The given data was invalid.",
     *               ),
     *               @OA\Property(
     *                   property="errors",
     *                   type="object",
     *                   description="An object containing validation errors for each field.",
     *                   @OA\Property(
     *                       property="body",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="user_id",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="post_id",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *           response="401",
     *           ref="#/components/responses/Unauthenticated"
     *       ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function store(){}

    /**
     * @OA\Get(
     *     path="/v1/comments/{id}",
     *     summary="View a Public Comment",
     *     description="Retrieves the details of a single comment. This endpoint is public and does not require authentication.",
     *     tags={"My comments"},
     *     @OA\Parameter(
     *         description="The ID of the comment to retrieve.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/searchFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortByFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortDirectionFilter"),
     *     @OA\Parameter(ref="#/components/parameters/relationshipsFilter"),
     *     @OA\Response(
     *         response="200",
     *         description="Comment data retrieved successfully.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Comment retrieved successfully."
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Comment",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The comment with the specified ID was not found.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Comment not found.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function show(){}

    /**
     * @OA\Patch(
     *     path="/v1/comments/{id}",
     *     summary="Update My Comment",
     *     description="Updates the body of a comment belonging to the authenticated user. You can only edit comments you own.",
     *     tags={"My comments"},
     *     @OA\Parameter(
     *         description="The ID of the comment you want to update.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="9be0a1a8-115b-425d-a461-14881d22e47c"
     *     ),
     *     @OA\RequestBody(
     *           description="The new content for the comment's body.",
     *           required=false,
     *           @OA\JsonContent(
     *               @OA\Property(
     *                   property="body",
     *                   type="string",
     *                   description="The new content/text for the comment.",
     *                   example="This is an edited comment."
     *               ),
     *               @OA\Property(
     *                   property="post_id",
     *                   type="string",
     *                   description="Move the comment to a different post by its ID."
     *               ),
     *           ),
     *       ),
     *     @OA\Response(
     *         response="200",
     *         description="Your comment was successfully updated.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Comment updated successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Comment",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="The comment with the specified ID was not found.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="",
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response="422",
     *          description="The given data was invalid.",
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="",
     *                ),
     *                @OA\Property(
     *                    property="errors",
     *                    type="object",
     *                    description="An object containing validation errors for each field.",
     *                    @OA\Property(
     *                        property="title",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                    @OA\Property(
     *                        property="body",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                    @OA\Property(
     *                        property="user_id",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                    @OA\Property(
     *                        property="post_id",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                ),
     *          ),
     *      ),
     *     @OA\Response(
     *           response="401",
     *           ref="#/components/responses/Unauthenticated"
     *       ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/v1/comments/{id}",
     *     summary="Delete My Comment",
     *     description="Permanently deletes a comment you own. **This action cannot be undone.**",
     *     tags={"My comments"},
     *     @OA\Parameter(
     *         description="The ID of the comment you want to delete.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="9be0a1a8-115b-425d-a461-14881d22e47c"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Your comment was successfully deleted.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Comment deleted successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Comment",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The comment with the specified ID was not found.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *           response="401",
     *           ref="#/components/responses/Unauthenticated"
     *       ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function destroy(){}
}
