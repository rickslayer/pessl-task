<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;

class UserTest extends TestCase
{
    /** @test */
    public function get_parameters_for_the_user_through_the_email()
    {
        $this->get('/api/user?userEmail=paulo@actio.net.br');

        $this->response->assertStatus(200);
        $this->assertCount(5,Cache::store('redis')->get("paulo@actio.net.br_data"));
    } 
    
    /** @test */
    public function add_parameters_for_the_user_through_the_form()
    {
        $data = [
             "userEmail" => "mail@teste.com",
             "battery" => "10",
             "rh" => "10",
             "air" => "10",
             "dw" => "10"
        ];
        
        $this->assertArrayHasKey("userEmail",$data);
        $this->post('/api/user',$data);
        $this->assertCount(5,Cache::store('redis')->get("mail@teste.com_data"));
        $this->response->assertStatus(202);
    }
}