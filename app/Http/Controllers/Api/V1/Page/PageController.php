<?php

namespace App\Http\Controllers\Api\V1\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Services\Page\PageService;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Page",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="Уникальный идентификатор страницы"),
 *     @OA\Property(property="name", type="string", example="Главная", description="Название страницы"),
 *     @OA\Property(property="slug", type="string", nullable=true, example="makeup", description="URL-слаг страницы (может быть null)")
 * )
 */

class PageController extends Controller
{

    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }



    /**
     * @OA\Get(
     *     path="/pages",
     *     summary="Получить список всех страниц",
     *     description="Возвращает массив всех доступных страниц с их идентификаторами, названиями и слагами. Требуется авторизация.",
     *     tags={"Страницы"},
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
     *         description="Успешный ответ. Возвращает массив страниц.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Page"),
     *             example={
     *                 {
     *                     "id": 1,
     *                     "name": "Главная",
     *                     "slug": null
     *                 },
     *                 {
     *                     "id": 2,
     *                     "name": "Макияж",
     *                     "slug": "makeup"
     *                 }
     *             }
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
     *         response=403,
     *         description="Доступ запрещен",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
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
    public function getList() {
        $pages = $this->pageService->getList();
        return response()->json($pages, 200);
    }




/**
 * @OA\Get(
 *     path="/pages/{page_id}",
 *     summary="Получить страницу по ID",
 *     description="Возвращает детальную информацию о странице с указанным идентификатором. Требуется авторизация.",
 *     tags={"Страницы"},
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
 *     @OA\Parameter(
 *         name="page_id",
 *         in="path",
 *         required=true,
 *         description="ID страницы",
 *         @OA\Schema(
 *             type="integer",
 *             example=11
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Успешный ответ. Возвращает детальную информацию о странице.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=11),
 *             @OA\Property(property="type", type="string", example="style"),
 *             @OA\Property(property="name", type="string", example="Street съёмка"),
 *             @OA\Property(property="slug", type="string", example="street-shooting"),
 *             @OA\Property(property="title", type="string", example="Стрит съемка в Москве"),
 *             @OA\Property(property="description", type="string", example="Профессиональная стрит-фотография в городских условиях"),
 *             @OA\Property(property="h1", type="string", example="Стрит съемка: запечатлей моменты города"),
 *             @OA\Property(property="banner_text", type="string", example="Скидка 20% на первую съемку"),
 *             @OA\Property(property="html1", type="string", example="<div class='content'><h3>Почему выбирают нас</h3><p>Мы работаем с 2010 года</p></div>"),
 *             @OA\Property(property="html2", type="string", example="<div class='portfolio'><h3>Примеры работ</h3><p>Скоро будут добавлены</p></div>"),
 *             @OA\Property(property="status", type="string", example="active", enum={"active", "arhived"}),
 *             
 *             @OA\Property(
 *                 property="tariffs",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Базовый"),
 *                     @OA\Property(property="price", type="string", example="30"),
 *                     @OA\Property(
 *                         property="tariff_list",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer", example=1),
 *                             @OA\Property(property="title", type="string", example="базовый минимум 1"),
 *                             @OA\Property(property="description", type="string", example="кольцо купи")
 *                         )
 *                     )
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="advantages",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="Опытные фотографы"),
 *                     @OA\Property(property="description", type="string", example="10 лет опыта")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="faqs",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="question", type="string", example="Как долго длится съемка?"),
 *                     @OA\Property(property="answer", type="string", example="От 1 до 3 часов")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="images",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="type", type="string", example="gallery", enum={"gallery", "banner"}),
 *                     @OA\Property(property="path", type="string", nullable=true, example=null),
 *                     @OA\Property(property="alt", type="string", example="Стрит съемка в центре")
 *                 )
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
 *         response=403,
 *         description="Страница не найдена",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Page not found")
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
    public function get($page_id) {
        $page = $this->pageService->get($page_id);
        if ($page) return response()->json($page, 200);
        return response()->json(['success' => false, 'error' => 'Page not found'], 403);
    }




/**
 * @OA\Patch(
 *     path="/pages/{page_id}",
 *     summary="Обновить страницу",
 *     description="Обновляет существующую страницу по ID. Требуется авторизация.",
 *     tags={"Страницы"},
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
 *     @OA\Parameter(
 *         name="page_id",
 *         in="path",
 *         required=true,
 *         description="ID страницы для обновления",
 *         @OA\Schema(
 *             type="integer",
 *             example=11
 *         )
 *     ),
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для обновления страницы",
 *         @OA\JsonContent(
 *             required={
 *                 "title", "description", "h1", "banner_text", 
 *                 "html1", "html2", "status", "tariffs", 
 *                 "advantages", "faqs", "images"
 *             },
 *             @OA\Property(property="title", type="string", example="Стрит съемка в Москве"),
 *             @OA\Property(property="description", type="string", example="Профессиональная стрит-фотография в городских условиях"),
 *             @OA\Property(property="h1", type="string", example="Стрит съемка: запечатлей моменты города"),
 *             @OA\Property(property="banner_text", type="string", example="Скидка 20% на первую съемку"),
 *             @OA\Property(property="html1", type="string", example="<div class='content'><h3>Почему выбирают нас</h3><p>Мы работаем с 2010 года</p></div>"),
 *             @OA\Property(property="html2", type="string", example="<div class='portfolio'><h3>Примеры работ</h3><p>Скоро будут добавлены</p></div>"),
 *             @OA\Property(property="status", type="string", example="active", enum={"active", "arhived"}),
 *             
 *             @OA\Property(
 *                 property="tariffs",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     required={"name", "price", "tariff_list"},
 *                     @OA\Property(property="name", type="string", example="Базовый"),
 *                     @OA\Property(property="price", type="string", example="30"),
 *                     @OA\Property(
 *                         property="tariff_list",
 *                         type="array",
 *                         minItems=1,
 *                         @OA\Items(
 *                             required={"title", "description"},
 *                             @OA\Property(property="title", type="string", example="базовый минимум 1"),
 *                             @OA\Property(property="description", type="string", example="кольцо купи")
 *                         )
 *                     )
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="advantages",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     required={"title", "description"},
 *                     @OA\Property(property="title", type="string", example="Опытные фотографы"),
 *                     @OA\Property(property="description", type="string", example="10 лет опыта")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="faqs",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     required={"question", "answer"},
 *                     @OA\Property(property="question", type="string", example="Как долго длится съемка?"),
 *                     @OA\Property(property="answer", type="string", example="От 1 до 3 часов")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="images",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     required={"type", "url", "alt"},
 *                     @OA\Property(property="type", type="string", example="gallery", enum={"gallery", "banner"}),
 *                     @OA\Property(property="url", type="string", example="/storage/images/street-1.jpg"),
 *                     @OA\Property(property="alt", type="string", example="Стрит съемка в центре")
 *                 )
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Страница успешно обновлена. Возвращает обновленные данные страницы.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=11),
 *             @OA\Property(property="type", type="string", example="style"),
 *             @OA\Property(property="name", type="string", example="Street съёмка"),
 *             @OA\Property(property="slug", type="string", example="street-shooting"),
 *             @OA\Property(property="title", type="string", example="Стрит съемка в Москве"),
 *             @OA\Property(property="description", type="string", example="Профессиональная стрит-фотография в городских условиях"),
 *             @OA\Property(property="h1", type="string", example="Стрит съемка: запечатлей моменты города"),
 *             @OA\Property(property="banner_text", type="string", example="Скидка 20% на первую съемку"),
 *             @OA\Property(property="html1", type="string", example="<div class='content'><h3>Почему выбирают нас</h3><p>Мы работаем с 2010 года</p></div>"),
 *             @OA\Property(property="html2", type="string", example="<div class='portfolio'><h3>Примеры работ</h3><p>Скоро будут добавлены</p></div>"),
 *             @OA\Property(property="status", type="string", example="active", enum={"active", "arhived"}),
 *             
 *             @OA\Property(
 *                 property="tariffs",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="name", type="string", example="Базовый"),
 *                     @OA\Property(property="price", type="string", example="30"),
 *                     @OA\Property(
 *                         property="tariff_list",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(property="id", type="integer", example=7),
 *                             @OA\Property(property="title", type="string", example="базовый минимум 1"),
 *                             @OA\Property(property="description", type="string", example="кольцо купи")
 *                         )
 *                     )
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="advantages",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="title", type="string", example="Опытные фотографы"),
 *                     @OA\Property(property="description", type="string", example="10 лет опыта")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="faqs",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=2),
 *                     @OA\Property(property="question", type="string", example="Как долго длится съемка?"),
 *                     @OA\Property(property="answer", type="string", example="От 1 до 3 часов")
 *                 )
 *             ),
 *             
 *             @OA\Property(
 *                 property="images",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="type", type="string", example="gallery", enum={"gallery", "banner"}),
 *                     @OA\Property(property="path", type="string", nullable=true, example=null),
 *                     @OA\Property(property="alt", type="string", example="Стрит съемка в центре")
 *                 )
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
 *         response=403,
 *         description="Страница не найдена",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Page not found")
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
 *                     property="title",
 *                     type="array",
 *                     @OA\Items(type="string", example="The title field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="tariffs",
 *                     type="array",
 *                     @OA\Items(type="string", example="The tariffs field is required.")
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
    public function edit($page_id, Request $request) {
        $validator = Validator::make($request->all(), [
            // Основные поля страницы
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'h1' => 'required|string|max:255',
            'banner_text' => 'required|string',
            'html1' => 'required|string',
            'html2' => 'required|string',
            'status' => 'required|string|in:active,arhived',
            
            // Тарифы
            'tariffs' => 'required|array|min:1',
            'tariffs.*.name' => 'required|string|max:255',
            'tariffs.*.price' => 'required|numeric|min:0',
            
            // Tariff lists (вложенные в тарифы)
            'tariffs.*.tariff_list' => 'required|array|min:1',
            'tariffs.*.tariff_list.*.title' => 'required|string|max:255',
            'tariffs.*.tariff_list.*.description' => 'required|string',
            
            // Преимущества
            'advantages' => 'required|array|min:1',
            'advantages.*.title' => 'required|string|max:255',
            'advantages.*.description' => 'required|string',
            
            // FAQ
            'faqs' => 'required|array|min:1',
            'faqs.*.question' => 'required|string|max:255',
            'faqs.*.answer' => 'required|string',
            
            // Изображения
            'images' => 'required|array|min:1',
            'images.*.type' => 'required|string|in:gallery,banner',
            'images.*.url' => 'required|string|max:255',
            'images.*.alt' => 'required|string|max:255',
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
        $page = $this->pageService->edit($page_id, $request);
        return response()->json($page, 200);
    }

}