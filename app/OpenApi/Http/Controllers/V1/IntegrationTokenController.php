<?php

namespace App\OpenApi\Http\Controllers\V1;

use OpenApi\Annotations as OA;

class IntegrationTokenController
{
    /**
     * @OA\Get(
     *     path="/v1/admin/integration-token",
     *     summary="",
     *     description="",
     *     tags={"Integration Token"},
     *     security={
     *          {"sanctum": {}}
     *      },
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Integration Token details retrieved successfully."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   type="object",
     *                   description="",
     *                   @OA\Property(
     *                       property="has_active_token",
     *                       type="bool",
     *                   ),
     *                   @OA\Property(
     *                       property="last_used_at",
     *                       type="datetime",
     *                   ),
     *                   @OA\Property(
     *                        property="created_at",
     *                        type="datetime",
     *                    ),
     *               ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response="401",
     *          ref="#/components/responses/Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response="403",
     *          ref="#/components/responses/Forbidden"
     *      ),
     * )
     */
    public function show(){}

    /**
     * @OA\Post(
     *     path="/v1/admin/integration-token",
     *     summary="",
     *     description="",
     *     tags={"Integration Token"},
     *     security={
     *          {"sanctum": {}}
     *      },
     *     @OA\Response(
     *         response="201",
     *         description="",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Integration Token generated successfully. Please store it in a safe place, as it will not be shown again."
     *               ),
     *               @OA\Property(
     *                   property="data",
     *                   type="object",
     *                   description="",
     *                   @OA\Property(
     *                       property="token",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                        property="created_at",
     *                        type="datetime",
     *                    ),
     *               ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response="409",
     *          description="",
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="This admin already has an active Integration Token."
     *                ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="401",
     *          ref="#/components/responses/Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response="403",
     *          ref="#/components/responses/Forbidden"
     *      ),
     * )
     */
    public function store(){}

    /**
     * @OA\Delete(
     *     path="/v1/admin/integration-token",
     *     summary="",
     *     description="",
     *     tags={"Integration Token"},
     *     security={
     *          {"sanctum": {}}
     *      },
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Integration Token revoked successfully."
     *               ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response="401",
     *          ref="#/components/responses/Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response="403",
     *          ref="#/components/responses/Forbidden"
     *      ),
     * )
     */
    public function destroy(){}
}
