<?php
​
namespace Tests\Feature;
​
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Client\ClientInterface;
​
class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_client_user_data_success()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => 'test',
            'address1' => 'test',
            'address2' => 'test',
            'city' => 'test',
            'state' => 'test',
            'country' => 'test',
            'country' => 'test',
            'zipCode' => 'test',
            'phoneNo1' => 'test',
            'phoneNo2' => 'test',
            'user' => [
                'firstName' => 'user',
                'lastName' => 'last',
                'email' => 'email@mail.com',
                'password' => 'user',
                'passwordConfirmation' => 'user',
                'phone' => 'user'
            ]
        ];
        
        $response = $this->json('POST', '/api/register',$data);
        $response->assertStatus(200);
        $response->assertSuccessful();
    }
​
    public function test_add_client_user_data_validation()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => '', //validation check
            'address1' => 'test',
            'address2' => 'test',
            'city' => 'test',
            'state' => 'test',
            'country' => 'test',
            'country' => 'test',
            'zipCode' => 'test',
            'phoneNo1' => 'test',
            'phoneNo2' => 'test',
            'user' => [
                'firstName' => 'user',
                'lastName' => 'last',
                'email' => 'email@mail.com',
                'password' => 'user',
                'passwordConfirmation' => 'user',
                'phone' => 'user'
            ]
        ];
        
        $response = $this->json('POST', '/api/register',$data);
        $response->assertStatus(400);
    }
    
    public function test_get_client_user_data_success()
    {        
        $response = $this->json('GET', '/api/account');
        $response->assertSuccessful();
    }
}