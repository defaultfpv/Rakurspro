<?php

namespace App\Http\Controllers\Api\V1;

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *     @OA\Info(
 *         title="RakursPRO",
 *         version="1.0.0",
 *         description="API документация для проекта RakursPRO"
 *     ),
 *     @OA\Server(
 *         url="https://api.pro-rakurs.ru/api/v1",
 *         description="Prodaction server"
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Авторизация",
 *     description="Методы для авторизации и аутентификации пользователей"
 * )
 * 
 * @OA\Tag(
 *     name="Страницы",
 *     description="Управление страницами"
 * )
 * 
 * @OA\Tag(
 *     name="Файлы",
 *     description="Работа с файлами"
 * )
 * 
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="Введите токен в формате: Bearer {token}"
 *     )
 * )
 * 
 * @OA\PathItem(
 *     path="/api/v1"
 * )
 */
class Swagger
{
    // Этот класс служит только для размещения аннотаций OpenAPI
}