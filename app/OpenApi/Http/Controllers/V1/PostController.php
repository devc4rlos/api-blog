<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class PostController
{
    /**
     * @OA\Get(
     *     path="/v1/admin/posts",
     *     summary="List Posts",
     *     description="Retrieves a paginated list of posts. Results can be filtered, sorted, and searched.",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
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
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
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
     *     path="/v1/admin/posts/{id}/comments",
     *     summary="List a Post's Comments",
     *     description="Retrieves a paginated list of all comments associated with a specific post. Requires administrator privileges.",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The ID of the post whose comments are to be retrieved.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
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
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function comments(){}

    /**
     * @OA\Post(
     *     path="/v1/admin/posts",
     *     summary="Create a New Post",
     *     description="Creates a new post. The authenticated user will be set as the author. `multipart/form-data` is required for image uploads.",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\RequestBody(
     *          required=true,
     *          description="New post data.",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"description", "title", "body", "status"},
     *                  @OA\Property(
     *                      property="description",
     *                      description="A short description or summary of the post.",
     *                      type="string",
     *                      example="A brief look at modern API development."
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="The title of the post.",
     *                      example="The Art of API Documentation",
     *                  ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="The main content of the post, can be text or Markdown.",
     *                     example="# Writing good documentation is crucial for API adoption..."
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="The status of the post (e.g., 'published', 'draft').",
     *                     example="published"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="The URL-friendly slug. If not provided, it will be generated from the title.",
     *                     example="the-art-of-api-documentation"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Featured image file (optional).",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Post created successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Post created successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Post",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Post created successfully.",
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
     *                       property="title",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="description",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="slug",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="body",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="image",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="status",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function store(){}

    /**
     * @OA\Get(
     *     path="/v1/admin/posts/{id}",
     *     summary="Get a Specific Post",
     *     description="Retrieves the details of a specific post by its ID.",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The ID of the post to retrieve.",
     *         in="path",
     *         name="id",
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
     *         description="The post with the specified ID was not found.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The resource could not be found.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function show(){}

    /**
     * @OA\Patch(
     *     path="/v1/admin/posts/{id}",
     *     summary="Update an Existing Post",
     *     description="Updates a post's data. Only the fields provided in the request will be updated (partial update).",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The ID of the post to update.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *           required=false,
     *           description="Post fields to be updated. No fields are required.",
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   type="object",
     *                   @OA\Property(
     *                       property="description",
     *                       description="short description or summary of the post.",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="title",
     *                       type="string",
     *                       description="The title of the post.",
     *                   ),
     *                  @OA\Property(
     *                      property="body",
     *                      type="string",
     *                      description="The main content of the post, can be text or Markdown.",
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="string",
     *                      description="The status of the post (e.g., 'published', 'draft')."
     *                  ),
     *                  @OA\Property(
     *                      property="slug",
     *                      type="string",
     *                      description="The URL-friendly slug."
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      description="New featured image file. To remove the current image, you may need a separate mechanism or send a specific value.",
     *                      type="string",
     *                      format="binary"
     *                  ),
     *               )
     *           )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Post updated successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Post updated successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Post",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="The post with the specified ID was not found.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The resource could not be found.",
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response="422",
     *          description="he given data was invalid.",
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="The given data was invalid.",
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
     *                        property="description",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                    @OA\Property(
     *                        property="slug",
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
     *                        property="image",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                    @OA\Property(
     *                        property="status",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                ),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/v1/admin/posts/{id}",
     *     summary="Delete a Post",
     *     description="Permanently deletes a post from the system. **This action cannot be undone.**",
     *     tags={"Posts"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The ID of the post to delete.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Post deleted successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Post deleted successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/Post",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The post with the specified ID was not found.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The resource could not be found.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         ref="#/components/responses/Forbidden"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function destroy(){}
}
