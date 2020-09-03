<?php
namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Metos\Helpers\DataParser; 
use Metos\Services\EmailSender;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Metos\Services\AlertService;
use Metos\Services\LogService;
use Metos\Services\PayloadService;

class UserController extends Controller
{
    public function add(Request $request) : JsonResponse
    {
        $data = $request->all();
        $email = $data['userEmail'];
        if ($data != null && isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Cache::store('redis')->forever("{$email}_data", $data);

            return response()
            ->json([
                "message" => "save with success",
                "success" => true
            ],202);
        } else {
            return response()
                ->json([
                    "message" => "Something was wrong. Check your email",
                    "success" => false
                ],200);
        }
        
    }

    public function get(Request $request)
    {
        $email = $request->input('userEmail');
        $saved_info = Cache::store('redis')->get("{$email}_data");
        if($saved_info != null) {
            return response()
            ->json([
                "message" => $saved_info,
                "success" => true
            ],200);
            
        } else {
            return response()
            ->json([
                "message" => "No data saved to retrive",
                "success" => false
            ],200);
        }
    }
}