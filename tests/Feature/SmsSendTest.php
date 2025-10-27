<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SmsSendTest extends TestCase
{
//    use RefreshDatabase;

    #[Test]
    public function it_requires_mandatory_fields(): void
    {
        $response = $this->postJson('/api/sms/send', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_sends_sms_with_valid_data(): void
    {
        $payload = [
            'mobile' => '09120000000',
            'message' => 'Hello from test message',
            'expired_at' => now()->addDay()->toISOString(),
            'due_at' => now()->addHours(2)->toISOString(),
            'provider' => null,
            'line' => null,
            'is_active' => true,
        ];

        $response = $this->postJson('/api/sms/send', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'value' => [
                    'sms' => [
                        'id',
                        'mobile',
                        'message',
                        'status',
                        'expiredAt',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('sms', [
            'mobile' => '09120000000',
            'message' => 'Hello from test message',
        ]);
    }
}
