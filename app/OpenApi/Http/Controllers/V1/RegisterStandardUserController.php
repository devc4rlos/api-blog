<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/v1/register",
 *     summary="Register a new user",
 *     description="Creates a new user account in the system. After successful registration, the user will be able to authenticate to access protected resources.",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         description="Data required to create the user account.",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "password_confirmation"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="The user's full name.",
 *                 example="John Doe",
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="The user's unique email address. It will be used for login.",
 *                 example="john.doe@example.com",
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 description="The user's password. Must be at least 8 characters long.",
 *                 example="password123",
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 description="Confirmation of the user's password. Must match the 'password' field.",
 *                 example="password123"
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="User created successfully. The response includes the new user's data.",
 *         @OA\JsonContent(
 *             type="object",
 *              @OA\Property(
 *                   property="message",
 *                   type="string",
 *                   example="User created successfully.",
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   ref="#/components/schemas/StandardUser",
 *               ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="The submitted data failed validation. The response contains details about the errors.",
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
 *         response="429",
 *         ref="#/components/responses/TooManyRequests"
 *     ),
 * )
 */
class RegisterStandardUserController {}
