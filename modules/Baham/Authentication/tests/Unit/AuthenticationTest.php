<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;


class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_requires_a_user_name()
    {
        $response = $this->postJson('api/v1/user/signup', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonMissingValidationErrors('user_name');
    }

    /** @test */
    public function it_creates_a_new_user_and_returns_a_token_on_success()
    {
        $userData = [
            'user_name' => 'test_user'
        ];

        $response = $this->postJson('api/v1/user/signup', $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'token'
                ],
                'message'
            ]);

        // Verify that the user was created and logged in
        $this->assertAuthenticated();
        $this->assertNotNull(auth()->user());
    }


}
