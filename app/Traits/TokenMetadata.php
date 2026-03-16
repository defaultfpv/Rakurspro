<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

trait TokenMetadata
{
    protected function createTokenWithMetadata(Request $request, $user, string $tokenName, array $abilities = [])
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        $token = $user->createToken($tokenName, $abilities);
        
        // Определяем тип устройства
        $deviceType = 'unknown';
        if ($agent->isMobile()) $deviceType = 'mobile';
        elseif ($agent->isTablet()) $deviceType = 'tablet';
        elseif ($agent->isDesktop()) $deviceType = 'desktop';
        elseif ($agent->isRobot()) $deviceType = 'bot';
        
        $browser = $agent->browser() ?: 'unknown';
        $platform = $agent->platform() ?: 'unknown';
        
        // ПРИНУДИТЕЛЬНОЕ обновление через DB фасаду
        $updated = DB::table('personal_access_tokens')
            ->where('id', $token->accessToken->id)
            ->update([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $deviceType,
                'browser' => $browser,
                'platform' => $platform,
                'last_used_at' => now(),
            ]);
        
        Log::info('Token metadata ' . ($updated ? 'saved' : 'failed'), [
            'token_id' => $token->accessToken->id,
            'ip' => $request->ip(),
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
            'updated_rows' => $updated
        ]);
        
        return $token;
    }
}