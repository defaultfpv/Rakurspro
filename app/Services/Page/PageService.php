<?php

namespace App\Services\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Page;

class PageService
{

    public function getList() {
        $pages = Page::get();
        $cleanPages = [];
        foreach ($pages as $page) {
            $cleanPages[] = [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'type' =>$page->type
            ];
        }
        return $cleanPages;
    }


    public function get($pageId) {
        $page = Page::with([
            'tariffs' => function($query) {
                $query->with('tariff_list'); 
            },
            'advantages',
            'faqs',
            'images'
        ])->find($pageId);
        if (!$page) return false;
        return $page; 
    }


    public function edit($pageId, $request) {
        DB::beginTransaction();
    
        try {
            $page = Page::findOrFail($pageId);
        
            // Обновляем страницу
            $page->update([
                'title' => $request->title,
                'description' => $request->description,
                'h1' => $request->h1,
                'banner_text' => $request->banner_text,
                'html1' => $request->html1,
                'html2' => $request->html2,
                'status' => $request->status,
            ]);

            // Удаляем старые связи
            $page->tariffs()->delete();
            $page->advantages()->delete();
            $page->faqs()->delete();
            $page->images()->delete();

            // Создаем новые тарифы
            foreach ($request->tariffs as $tariffData) {
                $tariff = $page->tariffs()->create([
                    'name' => $tariffData['name'],
                    'price' => $tariffData['price']
                ]);
            
                if (!empty($tariffData['tariff_list'])) {
                    $tariff->tariff_list()->createMany($tariffData['tariff_list']);
                }
            }

            // Создаем остальные связи
            $page->advantages()->createMany($request->advantages);
            $page->faqs()->createMany($request->faqs);
            $page->images()->createMany($request->images);

            DB::commit();
        
            return $page->load(['tariffs.tariff_list', 'advantages', 'faqs', 'images']);
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при обновлении страницы ID ' . $pageId . ': ' . $e->getMessage());
            throw new \Exception('Ошибка при обновлении страницы: ' . $e->getMessage());
            return false;
        }
    }

}