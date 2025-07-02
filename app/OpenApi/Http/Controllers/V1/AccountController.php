<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class AccountController
{
    /**
     * @OA\Get(
     *     path="/v1/account",
     *     summary="Get authenticated user account",
     *     description="Retrieves the profile information of the currently authenticated user.",
     *     tags={"Account"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Parameter(ref="#/components/parameters/searchFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortByFilter"),
     *     @OA\Parameter(ref="#/components/parameters/sortDirectionFilter"),
     *     @OA\Parameter(ref="#/components/parameters/relationshipsFilter"),
     *     @OA\Response(
     *          response="200",
     *          description="User account details retrieved successfully.",
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="User account retrieved successfully.",
     *                ),
     *                @OA\Property(
     *                    property="data",
     *                    ref="#/components/schemas/User",
     *                ),
     *          ),
     *      ),
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
     *     path="/v1/account",
     *     summary="Update authenticated user account",
     *     description="Updates the profile information of the currently authenticated user. Fields not provided will not be changed.",
     *     tags={"Account"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Data to update the user's account. All fields are optional for a PATCH request.",
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Jane Doe",
     *                 description="The user's full name.",
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="jane.doe@example.com",
     *                 description="The user's unique email address. Must be a valid email format and unique in the system.",
     *             ),
     *             @OA\Property(
     *                 property="is_admin",
     *                 type="bool",
     *                 example=false,
     *                 description="Indicates if the user has administrator privileges (true) or not (false). (Note: Updating this field may require specific administrator permissions).",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User account updated successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Account updated successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/User",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error. The provided request data is invalid.",
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
     *               ),
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
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/v1/account",
     *     summary="Delete Authenticated User Account",
     *     description="Deletes the currently authenticated user's account. This action is irreversible.",
     *     tags={"Account"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="User account successfully deleted.",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example=""
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/User",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="403",
     *          ref="#/components/responses/Forbidden"
     *      ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     *      @OA\Response(
     *          response="429",
     *          ref="#/components/responses/TooManyRequests"
     *      ),
     * )
     */
    public function destroy(){}
}
