<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class UserController
{
    /**
     * @OA\Get(
     *     path="/v1/admin/users",
     *     summary="Retrieve a paginated list of users",
     *     description="Fetches a list of all users, allowing for pagination, searching, sorting, and relationship embedding. This endpoint is useful for administrative views or user directories.",
     *     tags={"Users"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(ref="#/components/parameters/pageFilter"),
     *     @OA\Parameter(ref="#/components/parameters/searchFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortByFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortDirectionFilter"),
     *     @OA\Response(
     *         response="200",
     *         description="A paginated list of users was successfully retrieved.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   type="array",
     *                   @OA\Items(ref="#/components/schemas/User")
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
     * @OA\Post(
     *     path="/v1/admin/users",
     *     summary="Create a new user",
     *     description="Registers a new user in the system with the provided information.",
     *     tags={"Users"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\RequestBody(
     *         description="The data required to create a new user.",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="The full name of the user",
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="A unique email address for the user. This will be used for login.",
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="The user's password. Must be at least 8 characters long.",
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 format="password",
     *                 description="Confirmation of the user's password. Must match the 'password' field.",
     *             ),
     *             @OA\Property(
     *                 property="is_admin",
     *                 type="bool",
     *                 description="Determines if the user has administrator permissions.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="The user was successfully created.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="User created successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/User",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="The request data failed validation.",
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
     *                       property="name",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="email",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Schema(type="string")
     *                       )
     *                   ),
     *                   @OA\Property(
     *                       property="password",
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
     *     path="/v1/admin/users/{id}",
     *     summary="Retrieve a specific user by ID",
     *     description="Fetches detailed information for a single user identified by their unique ID.",
     *     tags={"Users"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The unique identifier of the user.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The requested user data was successfully retrieved.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User retrieved successfully."
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/User",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The user with the specified ID was not found.",
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
     *     path="/v1/admin/users/{id}",
     *     summary="Update an existing user's information",
     *     description="Updates the details of an existing user. Only the fields provided in the request body will be updated.",
     *     tags={"Users"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The unique identifier of the user to be updated.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         description="The user data to be updated. At least one field must be provided.",
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="The new full name for the user.",
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="The new unique email address for the user.",
     *             ),
     *             @OA\Property(
     *                 property="is_admin",
     *                 type="bool",
     *                 description="Determines if the user has administrator permissions.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The user's information was successfully updated.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="User updated successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/User",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="The user with the specified ID was not found.",
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
     *          description="The request data failed validation.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The given data was invalid.",
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  description="An object containing validation errors for each field.",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Schema(type="string")
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Schema(type="string")
     *                      )
     *                  ),
     *              ),
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
     *     path="/v1/admin/users/{id}",
     *     summary="Delete a specific user",
     *     description="Permanently deletes a user from the system. This action is irreversible.",
     *     tags={"Users"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         description="The unique identifier of the user to be deleted.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The user was successfully deleted.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="User deleted successfully.",
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/User",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="The user with the specified ID was not found.",
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
