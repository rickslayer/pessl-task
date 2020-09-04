<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Metos\Services\EmailSenderService;

class EmailSenderTest extends TestCase
{
    /** @test */
    public function send_email_and_check_return()
    {
       $to = filter_var('contact@prra.dev', FILTER_VALIDATE_EMAIL);
       $html[] = "<h1>teste email</h1>";
       
       $this->assertEquals($to, 'contact@prra.dev');
       $this->assertNotNull($html);
       $this->assertIsArray($html);
       $response = EmailSenderService::sendEmail($to, $html);
       $this->assertIsArray($response);
       $this->assertArrayHasKey('status_code', $response);
       $this->assertEquals(202, $response['status_code']);
    } 
}