<?php
namespace Metos\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


class LogService
{
    public static function get()
    {
        $result = array();
        $logs = Cache::store('redis')->get('logs');
        if ($logs != null) {
            $result = json_encode($logs, JSON_PRETTY_PRINT);
        }
        return $result;
        
    }
    /**
     * Create log in Redis
     * 
     * @param string $message log to save
     * 
     * 
     */
    public static function add($message) : array
    {
        $logs = Cache::store('redis')->get('logs');

        if ($logs == null) {
            Cache::store('redis')->put('logs', $message, Carbon::now()->addHours(48));
        }else {
            $logs[] = $message;
            Cache::store('redis')->put('logs', $logs, Carbon::now()->addHours(48));
        }
        return array (
            "status_code" => 202,
            "message" => "Log created with success",
            "log" => $message
        );
    }
    /**
     * Delete all log Redis keys
     */
    public static function remove()
    {
        Cache::forget('logs');
    }
}