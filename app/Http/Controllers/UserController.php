<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class responsible for save in cache
 * data from frontend where the user
 * puts the parameters
 */
class UserController extends Controller
{
    /**
     * Add parameters to a user email
     * @param $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function add(Request $request) : JsonResponse
    {
        $data = $request->all();
        $email = $data['userEmail'];
        if ($data != null && isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Cache::store('redis')->forever("{$email}_data", $data);
            Cache::store('redis')->forever("MAIN_EMAIL", $email);

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

    /**
     * get parameters from redis to return to the frontend
     * @param $email
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function get(Request $request) :JsonResponse
    {
        $email = $request->input('userEmail');
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !isset($email)) {
            return response()
            ->json([
                "message" => "Check the e-mail format",
                "success" => false
            ],200);
        }

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
