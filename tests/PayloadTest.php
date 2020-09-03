<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Metos\Services\PayloadService;

class PayloadTest extends TestCase
{
    /** @test */
    public function get_payload_info_and_send_email()
    {
        $this->get('/api/payload');
        $this->response->assertStatus(202);
    }

    /** @test */
    public function validate_format_payload_and_email()
    {
        $payload = PayloadService::PrettyPayload();

        $check = filter_var($payload['email'], FILTER_VALIDATE_EMAIL);
        $this->assertEquals($check, $payload['email']);
        $this->assertArrayHasKey('header', $payload);
        $this->assertArrayHasKey('email', $payload);
        $this->assertArrayHasKey('data', $payload);
    }
}