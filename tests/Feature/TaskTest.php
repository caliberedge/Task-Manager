<?php

namespace Tests\Feature;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected function data(){
        return[
            "name" => "Ephraim Jeremiah",
            "email" => "ephraimjeremiah64@gmail.com",
            "address" => "Ipaja-Ayobo Lagos",
            "password" => "12345678"
        ];
    }
    protected function data1(){
        return[
            "title" => "Morning Devotion",
            "description" => "this is used to mange family fellowship",
            "priority" => "medium"
        ];
    }
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_can_register_user()
    {
        $this->withoutDeprecationHandling();

        $response = $this->post('/api/auth/register', $this->data());

        $response->assertSuccessful();
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'status' => true,
            'message' => 'User Successfully Registered',
        ]);   
     }

    public function test_can_login_user()
    {
        $this->withoutDeprecationHandling();
        
        $this->test_can_register_user();
        $response = $this->post('/api/auth/login', $this->data());

        $response->assertSuccessful();
        $response->assertStatus(200);
        $responseData = $response->json();

        $token= $responseData['token'] ?? null;
        return $token;
    }

    public function test_can_create_task()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $response = $this->post('/api/tasks', $this->data1(),[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(201);
    }

    public function test_can_get_all_task()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $response = $this->get('/api/tasks',[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(200);
    }
    public function test_title_and_description_can_be_updated()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $task = Task::create($this->data1());
        $response = $this->put('/api/tasks/'.$task->id,['title' => 'Evening Dinner', 'description' => 'this used for family meal'],[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => true,
            'message' => 'Task Updated Successfully',
        ]); 
    }

    public function test_can_view_single_task()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $task = Task::create($this->data1());
        $response = $this->get('/api/tasks/'.$task->id,[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(200);
    }

    public function test_can_view_all_task()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $response = $this->get('/api/tasks',[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(200);
    }
    public function test_can_delete_task()
    {
        $this->withoutDeprecationHandling();
        $token = $this->test_can_login_user();

        $task = Task::create($this->data1());
        $response = $this->delete('/api/tasks/'.$task->id,[
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertSuccessful();
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => true,
            'message' => 'Task Deleted Successfully',
        ]); 
    }
}
