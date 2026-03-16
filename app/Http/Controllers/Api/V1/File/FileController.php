<?php

namespace App\Http\Controllers\Api\V1\File;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\File\FileService;

/**
 * @OA\Schema(
 *     schema="FileUploadResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true, description="Флаг успешности операции"),
 *     @OA\Property(property="path", type="string", example="/storage/images/1773644834.png", description="Путь к загруженному файлу")
 * )
 */

class FileController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }



    /**
     * @OA\Post(
     *     path="/files",
     *     summary="Загрузить файл",
     *     description="Загружает файл на сервер. Поддерживаются изображения. Требуется авторизация.",
     *     tags={"Файлы"},
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
     *     @OA\RequestBody(
     *         required=true,
     *         description="Файл для загрузки",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Файл изображения (поддерживаются форматы: jpeg, png, jpg, gif, svg)"
     *                 )
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Файл успешно загружен",
     *         @OA\JsonContent(ref="#/components/schemas/FileUploadResponse")
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
     *         response=413,
     *         description="Файл слишком большой",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The file is too large")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=415,
     *         description="Неподдерживаемый тип файла",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unsupported file type")
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
     *                     property="file",
     *                     type="array",
     *                     @OA\Items(type="string", example="The file field is required.")
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
    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:5120'
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
            
        $path = $this->fileService->add($request->file('file'));
            
        if ($path) return response()->json(['success' => true, 'path' => $path], 201);
        return response()->json(['success' => false, 'message' => 'Failed to upload file'], 500);
    }

}