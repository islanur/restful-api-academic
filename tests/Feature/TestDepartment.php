<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TestDepartment extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testGetAllDepartmentSuccess(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        Department::factory()->create();
        $response = $this->get('/api/departments');

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', true)
                    ->has('message')->where('message', 'Success get all departments')
                    ->has('data', 2)
            );;
    }

    public function testCreateDepartmentSuccess(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $response = $this->post('/api/departments', [
            'code' => fake()->numerify('dept-####'),
            'name' => fake()->sentence(random_int(2, 4)),
            'alias' => fake()->lexify('??'),
        ]);

        $response->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', true)
                    ->has('message')->where('message', 'Success create a department')
                    ->has('data', 6)
            );;
    }

    public function testGetDepartmentSuccess(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $dept = Department::factory()->create();
        $response = $this->get('/api/departments/' . $dept->id);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('status')->where('status', true)
                    ->has('message')->where('message', 'Success get department')
                    ->has('data', 6)
            );;
    }
}
