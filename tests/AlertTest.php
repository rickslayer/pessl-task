<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Metos\Services\PayloadService;

class AlertTest extends TestCase
{
    /** @test */
    public function the_correct_format_of_payload()
    {
        $payload = PayloadService::PrettyPayload();

        $this->assertArrayHasKey('data',$payload);
        $this->assertArrayHasKey('email',$payload);
        $email = $payload['email'];
        $parameters = $payload['data'];
        $param_user = Cache::store("redis")->get("{$email}_data");

        $this->assertNotNull($param_user);
        $this->assertIsArray($parameters);

        $this->get('/api/payload');
        $this->response->assertStatus(202);

    }  

    /** @test */
    public function parameters_check_up()
    {
        $payload = PayloadService::PrettyPayload();
        $email = $payload['email'];
        $parameters = $payload['data'];
        $user_parameters = Cache::store("redis")->get("{$email}_data");

        $this->assertArrayHasKey('battery', $parameters);
        $this->assertArrayHasKey('rh_mx', $parameters);
        $this->assertArrayHasKey('air_mn', $parameters);
        $this->assertArrayHasKey('dew_mn', $parameters);
        $this->assertArrayHasKey('battery', $user_parameters);
        $this->assertArrayHasKey('rh', $user_parameters);
        $this->assertArrayHasKey('air', $user_parameters);
        $this->assertArrayHasKey('dw', $user_parameters);
        $this->assertLessThanOrEqual($parameters['battery'], $user_parameters['battery']);
        $this->assertLessThanOrEqual($user_parameters['rh'], $parameters['rh_mx']);
        $this->assertLessThanOrEqual($parameters['air_mn'], $user_parameters['air']);
        $this->assertLessThanOrEqual($parameters['dew_mn'], $user_parameters['dw']);

    }
}