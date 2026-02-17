<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('registers a user and returns an auth token', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Max Mustermann',
        'email' => 'max@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertCreated()
        ->assertJsonStructure(['success', 'message', 'data' => ['token', 'user' => ['id', 'name', 'email']]]);

    $user = User::where('email', 'max@example.com')->first();

    expect($user)->not->toBeNull();
    expect(Hash::check('secret123', $user->password))->toBeTrue();
});

it('returns 401 for invalid login credentials', function () {
    $user = User::factory()->create([
        'email' => 'max@example.com',
        'password' => 'secret123',
    ]);

    $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});

it('logs in an existing user and returns a token', function () {
    $user = User::factory()->create([
        'email' => 'max@example.com',
        'password' => 'secret123',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['success', 'message', 'data' => ['token', 'user' => ['id', 'email']]]);
});

it('protects user endpoints with sanctum authentication', function () {
    $this->getJson('/api/v1/users')->assertUnauthorized();
});

it('returns users list and allows fetching by id or email', function () {
    $authUser = User::factory()->create();
    $target = User::factory()->create(['email' => 'target@example.com']);

    $token = $authUser->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/users')
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->withToken($token)
        ->getJson('/api/v1/users/'.$target->id)
        ->assertOk()
        ->assertJsonPath('data.email', $target->email);

    $this->withToken($token)
        ->getJson('/api/v1/users/target@example.com')
        ->assertOk()
        ->assertJsonPath('data.id', $target->id);
});

it('allows user to update own details but blocks others', function () {
    $authUser = User::factory()->create();
    $otherUser = User::factory()->create();

    $token = $authUser->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->putJson('/api/v1/users/'.$authUser->id, [
            'name' => 'Updated Name',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Name');

    $this->withToken($token)
        ->putJson('/api/v1/users/'.$otherUser->id, [
            'name' => 'Not Allowed',
        ])
        ->assertForbidden();
});

it('logs out and revokes the current access token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/logout')
        ->assertOk()
        ->assertJsonPath('message', 'Logout successful.');

    expect($user->fresh()->tokens()->count())->toBe(0);
});
