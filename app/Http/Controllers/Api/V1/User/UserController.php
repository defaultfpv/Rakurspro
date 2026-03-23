<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{

/**
 * @OA\Get(
 *     path="/users/me",
 *     summary="Получить информацию о текущем пользователе",
 *     description="Возвращает данные авторизованного пользователя. Требуется авторизация.",
 *     tags={"Пользователи"},
 *     security={{"bearerAuth":{}}},
 *     
 *     @OA\Parameter(
 *         name="Accept",
 *         in="header",
 *         required=true,
 *         description="Тип принимаемого контента",
 *         @OA\Schema(
 *             type="string",
 *             default="application/json"
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Успешный ответ. Возвращает данные пользователя.",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1, description="ID пользователя"),
 *                 @OA\Property(property="first_name", type="string", nullable=true, example=null, description="Имя"),
 *                 @OA\Property(property="last_name", type="string", nullable=true, example=null, description="Фамилия"),
 *                 @OA\Property(property="role", type="string", example="admin", description="Роль пользователя"),
 *                 @OA\Property(property="email", type="string", example="babchenkod1999@gmail.com", description="Email"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2026-03-19T06:25:03.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2026-03-23T08:59:10.000000Z")
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=401,
 *         description="Неавторизован - отсутствует или недействителен токен доступа",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=500,
 *         description="Внутренняя ошибка сервера",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Server error")
 *         )
 *     )
 * )
 */
    public function me(Request $request) {
        $user = $request->user();  
        return response()->json(['user' => $user], 200);
    }

}