<?php

namespace App\Services\Email;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function send($request)
    {
        try {
            // Получаем email администратора
            $admin = User::where('role', 'admin')->first();
            $adminEmail = $admin ? $admin->email : config('mail.admin_email', 'admin@example.com');
            
            // Данные для письма
            $data = [
                'name' => $request['name'],
                'phone' => $request['phone'],
                'comment' => $request['comment'] ?? 'Не указан',
                'date' => now()->format('d.m.Y H:i:s'),
            ];

            // Отправка письма
            Mail::send('emails.client-request', $data, function ($message) use ($adminEmail, $data) {
                $message->to($adminEmail)
                    ->subject('Новая заявка от ' . $data['name'])
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Contact email sent successfully', [
                'name' => $data['name'],
                'phone' => $data['phone']
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}