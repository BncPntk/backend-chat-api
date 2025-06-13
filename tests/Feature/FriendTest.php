<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function user_can_send_friend_request_to_verified_user()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $target = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->postJson("/api/v1/friends/{$target->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Barátnak jelölés elküldve.',
            'mutual' => false
        ]);

        $this->assertDatabaseHas('friend_user', [
            'user_id' => $user->id,
            'friend_id' => $target->id,
        ]);
    }
}
