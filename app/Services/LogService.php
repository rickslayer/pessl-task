<?php
namespace Metos\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


class LogService
{
    public static function add($message)
    {
        $logs = Cache::store('redis')->get('logs');

        dd($logs);

        if ($logs == null) {
            Cache::store('redis')->put('logs', $message, 172800);
        }else {
            $logs[] = $message;
            Cache::store('redis')->put('logs', $logs, 172800);
        }
        return array (
            "status_code" => 202,
            "message" => "Log created with success"
        );

    }

    public static function remove()
    {
        Cache::forget('logs');
    }
}