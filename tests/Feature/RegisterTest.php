<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function test_user_can_register_and_verification_email_is_sent(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/register', [
            'name' => 'Feature Test',
            'email' => 'feature_test@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Sikeres regisztráció. A megerősítő link kiküldve a megadott email címre.',
        ]);

        $user = User::where('email', 'feature_test@gmail.com')->first();
        $this->assertNotNull($user);

        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
