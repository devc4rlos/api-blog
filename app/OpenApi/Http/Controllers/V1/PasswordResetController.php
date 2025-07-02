<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class PasswordResetController
{
    /**
     * @OA\Post(
     *     path="/v1/forgot-password",
     *     summary="Request Password Reset",
     *     description="Initiates the password reset process by sending an email with a reset code or link to the provided email address.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *          description="Data required to initiate the password reset process.",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  format="email",
     *                  example="user@example.com",
     *                  description="The email address associated with the account for which the password reset is requested.",
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Password reset instructions sent successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="If a user with this email exists in our system, a password reset link has been sent."
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation error. The provided request data is invalid.",
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
     *                        property="email",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                ),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="429",
     *         ref="#/components/responses/TooManyRequests"
     *     ),
     * )
     */
    public function forgotPassword(){}

    /**
     * @OA\Post(
     *     path="/v1/reset-password",
     *     summary="Reset Password",
     *     description="Completes the password reset process, setting a new password for the user using the validation code.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         description="The email address of the user whose password is being reset.",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "code", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="user@example.com",
     *                 description="The email address of the user whose password is being reset.",
     *             ),
     *              @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  example="123456",
     *                  description="The unique password reset code received via email.",
     *              ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="NewSecurePassword123",
     *                 description="The user's new password. Must be at least 8 characters long.",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Password successfully reset.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Your password has been successfully reset. You can now log in with your new password.",
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error or invalid/expired reset token. The provided request data is invalid or the reset process failed.",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Unable to reset your password. The request is invalid or has expired. Please try the password reset process again.",
     *               ),
     *               @OA\Property(
     *                    property="errors",
     *                    type="object",
     *                    description="An object containing validation errors for each field.",
     *                    @OA\Property(
     *                        property="password",
     *                        type="array",
     *                        @OA\Items(
     *                            @OA\Schema(type="string")
     *                        )
     *                    ),
     *                ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="429",
     *         ref="#/components/responses/TooManyRequests"
     *     ),
     * )
     */
    public function resetPassword(){}
}
