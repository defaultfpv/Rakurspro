<?php

namespace App\Http\Controllers\Api\V1\Email;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Email\EmailService;


class  EmailController extends Controller
{

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }



/**
 * @OA\Post(
 *     path="/email/send",
 *     summary="Отправить заявку на email",
 *     description="Отправляет данные клиента (имя, телефон, комментарий) на email администратора. Требуется авторизация.",
 *     tags={"Email"},
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
 *     @OA\Parameter(
 *         name="Content-Type",
 *         in="header",
 *         required=true,
 *         description="Тип отправляемого контента",
 *         @OA\Schema(
 *             type="string",
 *             default="application/json"
 *         )
 *     ),
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные клиента",
 *         @OA\JsonContent(
 *             required={"name", "phone"},
 *             @OA\Property(property="name", type="string", example="Даниил", description="Имя клиента"),
 *             @OA\Property(property="phone", type="string", example="79996346047", description="Номер телефона"),
 *             @OA\Property(property="comment", type="string", example="лололололололол лололо лол о", description="Комментарий (опционально)")
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Письмо успешно отправлено",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true)
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
 *         response=422,
 *         description="Ошибка валидации",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The name field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="phone",
 *                     type="array",
 *                     @OA\Items(type="string", example="The phone field is required.")
 *                 )
 *             )
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
    public function send(Request $request) {
        $validator = Validator::make($request->all(), [
            // Основные поля страницы
            'name' => 'required|string|max:255',
            'phone' => 'required|min:11',
            'comment' => 'string|max:1000'
        ]);
            
        if ($validator->fails()) {
            Log::warning('Login validation failed', [
                'errors' => $validator->errors()->toArray(),
                'email' => $request->email
            ]);
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors()
            ], 422);
        }

        $send = $this->emailService->send($request);
        if (!$send) return response()->json(['success' => false], 500);
        return response()->json(['success' => true], 200);
    }

}