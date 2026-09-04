<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpCountryService
{
    public function country(?string $ip): string
    {
        if (!$ip || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return 'Unknown';
        }

        return Cache::remember('ip-country:' . $ip, now()->addDay(), function () use ($ip) {
            try {
                $response = Http::timeout(2)->get('https://ipwho.is/' . rawurlencode($ip));

                return $response->successful() && $response->json('success') && $response->json('country')
                    ? (string) $response->json('country')
                    : 'Unknown';
            } catch (\Throwable) {
                return 'Unknown';
            }
        });
    }
}
