<?php

namespace App\OpenApi\Http\Controllers\V1\Auth;

use OpenApi\Annotations as OA;

class AuthenticateController {
    /**
     * @OA\Post(
     *     path="/v1/login",
     *     summary="Authenticate User",
     *     description="Authenticates a user with provided credentials and returns an access token for subsequent requests.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *          description="Required data to authenticate the user and obtain an access token.",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(
     *                  property="email",
     *                  format="email",
     *                  type="string",
     *                  description="The user's unique email address, used for login.",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  format="password",
     *                  description="The user's password. Must be at least 8 characters long.",
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Authentication successful. Returns the access token and basic user data.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Authentication successful. Access token has been generated."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="password",
     *                     description="Email address of the authenticated user.",
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="The JWT (JSON Web Token) access token to be used in subsequent requests via 'Authorization: Bearer <token>' header.",
     *                     example="1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
     *                 ),
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized. The provided credentials (email or password) are invalid.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The provided credentials are incorrect.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error. The provided request data is invalid.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid.",
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="An object containing validation errors for each field.",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Schema(type="string")
     *                     )
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function login(){}

    /**
     * @OA\Post(
     *     path="/v1/logout",
     *     summary="User Logout",
     *     description="Invalidates the authenticated user's current access token, effectively logging them out.",
     *     tags={"Authentication"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Logout successful. The access token has been invalidated.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Session terminated successfully."
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         ref="#/components/responses/Unauthenticated"
     *     ),
     * )
     */
    public function logout(){}
}
