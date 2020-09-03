<?php
namespace Metos\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Create log in Redis
 * 
 * @param string $message log to save
 * 
 * 172800 = 48 hours
 */
class LogService
{
    public static function add($message) : array
    {
        $logs = Cache::store('redis')->get('logs');

        if ($logs == null) {
            Cache::store('redis')->put('logs', $message, 172800);
        }else {
            $logs[] = $message;
            Cache::store('redis')->put('logs', $logs, 172800);
        }
        return array (
            "status_code" => 202,
            "message" => "Log created with success",
            "log" => $message
        );
    }

    public static function remove()
    {
        Cache::forget('logs');
    }
}