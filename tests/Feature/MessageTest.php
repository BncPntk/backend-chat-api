<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function test_user_can_send_message_to_friend(): void
    {
        // 2 verified user létrehozása
        $sender = User::factory()->create(['email_verified_at' => now()]);
        $receiver = User::factory()->create(['email_verified_at' => now()]);

        // Ismerősök
        $sender->friends()->attach($receiver->id);
        $receiver->friends()->attach($sender->id);

        $this->actingAs($sender);

        $response = $this->postJson("/api/v1/messages/{$receiver->id}", [
            'content' => 'teszt szöveg.'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'teszt szöveg.'
        ]);
    }

    #[Test] public function test_user_cannot_send_message_to_non_friend(): void
    {
        $sender = User::factory()->create(['email_verified_at' => now()]);
        $stranger = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($sender);

        $response = $this->postJson("/api/v1/messages/{$stranger->id}", [
            'content' => 'Nem ismerősnek üzenet küldés'
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Nem vagytok ismerősök.'
        ]);
    }
}
