<?php

use App\Domain\Client\Models\Client;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Ticket\Enums\TicketSource;

pest()->use(RefreshDatabase::class);

beforeEach(function () {

    $this->user = User::factory()->create(['extension' => '100']);

    // Устанавливаем тестовый токен
    config(['api.tokens.asterisk' => 'test-token-12345']);
});

describe('Asterisk Call Webhook', function () {
    test('can create ticket and call from asterisk webhook', function () {
        $data = [
            'phone' => '+37312345678',
            'extension' => '100',
            'lang' => 'ro',
        ];

        $response = $this->withToken('test-token-12345')
            ->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Ticket created successfully')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'client_id',
                    'user_id',
                    'source',
                ],
                'message',
                'success'
            ]);

        $this->assertDatabaseHas('clients', [
            'phone' => '+37312345678',
        ]);

        $this->assertDatabaseHas('tickets', [
            'user_id' => $this->user->id,
            'source' => TicketSource::Phone->value,
        ]);

        $this->assertDatabaseHas('calls', [
            'user_id' => $this->user->id,
            'type' => 'in',
        ]);
    });
/*
    test('uses existing client for known phone number', function () {
        $client = Client::factory()->create(['phone' => '+37312345678']);

        $data = [
            'phone' => '+37312345678',
            'extension' => '100',
            'lang' => 'ro',
        ];

        $response = $this->withToken('test-token-12345')
            ->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(200);

        $this->assertDatabaseCount('clients', 1);

        $this->assertDatabaseHas('tickets', [
            'client_id' => $client->id,
        ]);
    });

    test('requires phone number', function () {
        $data = [
            'extension' => '100',
            'lang' => 'ro',
        ];

        $response = $this->withToken('test-token-12345')
            ->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    });

    test('requires extension', function () {
        $data = [
            'phone' => '+37312345678',
            'lang' => 'ro',
        ];

        $response = $this->withToken('test-token-12345')
            ->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['extension']);
    });

    test('requires valid api token', function () {
        $data = [
            'phone' => '+37312345678',
            'extension' => '100',
            'lang' => 'ro',
        ];

        $response = $this->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Unauthorized');
    });

    test('rejects invalid api token', function () {
        $data = [
            'phone' => '+37312345678',
            'extension' => '100',
            'lang' => 'ro',
        ];

        $response = $this->withToken('invalid-token')
            ->postJson('/api/v1/asterisk/call', $data);

        $response->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Unauthorized');
    });

*/
});
