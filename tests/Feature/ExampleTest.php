<?php

namespace Tests\Feature;

use App\Models\Authorization\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // 1. Create a user with the 'admin' type to bypass permission checks
        $user = User::factory()->create([
            'user_type' => 'admin',
        ]);

        // 2. Act as the admin user and request the home page
        $response = $this->actingAs($user)->get('/');

        // 3. Assert the response is now 200
        $response->assertStatus(200);
    }
}
