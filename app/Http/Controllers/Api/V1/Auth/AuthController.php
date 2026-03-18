<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) 
    {
    }

/**
 * @OA\Post(
 *     path="/auth/login",
 *     summary="Авторизация пользователя",
 *     description="Вход в систему по email и паролю. Возвращает токен доступа",
 *     tags={"Авторизация"},
 *     security={},
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email", 
 *                 type="string", 
 *                 format="email",
 *                 example="babchenkod1999@gmail.com",
 *                 description="Email пользователя"
 *             ),
 *             @OA\Property(
 *                 property="password", 
 *                 type="string",
 *                 format="password",
 *                 example="123123123",
 *                 description="Пароль пользователя"
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Успешная авторизация",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="token", 
 *                 type="string", 
 *                 example="38|prYqATwTAR2aDoiMHiaViawYuKeamTI8OkVo2a2tb44db3db",
 *                 description="Токен доступа для авторизации в API"
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=400,
 *         description="Некорректный запрос",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email field is required")
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password field is required")
 *                 )
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=401,
 *         description="Неверные учетные данные",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid credentials")
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=403,
 *         description="Неверный API ключ",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid API key")
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=422,
 *         description="Ошибка валидации",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email must be a valid email address.")
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
    public function login(Request $request) {
        try {
            
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8|max:24'
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
            
            $token = $this->authService->login($request->email, $request->password);
            
            if ($token) {
                return response()->json(['token' => $token], 201);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Authorization failed: incorrect username or password'
            ], 401);
            
        } catch (\Exception $e) {
            Log::error('Failed to authentication', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to authentication'
            ], 500);
        }
    }




/**
 * @OA\Post(
 *     path="/auth/logout",
 *     summary="Выход из системы",
 *     description="Завершает сессию пользователя и инвалидирует токен доступа. Требуется авторизация.",
 *     tags={"Авторизация"},
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
 *     @OA\Response(
 *         response=200,
 *         description="Успешный выход из системы",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true, description="Флаг успешности операции")
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
    public function logout(Request $request) {
        try {
            $user = $request->user();
            $authDown = $this->authService->logout($user);
            if ($authDown) return response()->json(['success' => true], 200);
            
            return response()->json([
                'success' => false,
                'message' => 'Authorization failed: incorrect username or password'
            ], 401);
            
        } catch (\Exception $e) {
            Log::error('Failed to authentication', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to authentication'
            ], 500);
        }
    }
}