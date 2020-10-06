<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Metos\Services\PayloadService;

class DataParserTest extends TestCase
{   
    /** @test */
    public function get_the_email_linked_with_serial()
    {
        $cached_email = Cache::store('redis')->get('MAIN_EMAIL');
        $this->assertNotNull($cached_email);
        $env_email = getenv('MAIN_EMAIL');
        $this->assertNotNull($env_email);
        

    }
}