<?php

namespace App\Services\File;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileService
{

    public function add($file)
    {
        try {
            // Генерируем уникальное имя файла
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            
            // Путь для сохранения: public/images/
            $path = 'images';
            
            // Сохраняем файл в public disk
            $fullPath = $file->storeAs($path, $fileName, 'public');
            
            // Возвращаем путь для доступа через URL
            return '/storage/' . $fullPath;
            
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Сохраняет файл с оптимизацией (если нужно сжимать)
     */
    // public function addOptimized($file)
    // {
    //     try {
    //         // Генерируем имя
    //         $fileName = time() . '.webp'; // конвертируем в webp
    //         $path = 'images';
            
    //         // Оптимизируем изображение (требует intervention/image)
    //         $image = Image::make($file) // почему image подчеркивается?
    //             ->resize(1200, null, function ($constraint) {
    //                 $constraint->aspectRatio();
    //                 $constraint->upsize();
    //             })
    //             ->encode('webp', 80); // качество 80%
            
    //         // Сохраняем
    //         Storage::disk('public')->put($path . $fileName, $image);
            
    //         return '/storage/' . $path . $fileName;
            
    //     } catch (\Exception $e) {
    //         Log::error('File upload error: ' . $e->getMessage());
    //         return false;
    //     }
    // }
}