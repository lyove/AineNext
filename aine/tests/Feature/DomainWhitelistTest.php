<?php

use App\Models\Project;
use App\Models\ProjectOrigin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

function grantWhitelistPermission(User $user): void
{
    Permission::findOrCreate('access_api_access_settings', 'web');
    $user->givePermissionTo('access_api_access_settings');
}

test('can list whitelist origins', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'is_active' => true,
    ]);

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://staging.example.com',
        'abilities' => ['read', 'create'],
        'is_active' => false,
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('projects.settings.whitelist.index', $project->id));

    $response->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment(['origin' => 'https://example.com'])
        ->assertJsonFragment(['origin' => 'https://staging.example.com']);
});

test('can add origin to whitelist', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'https://example.com',
            'description' => 'Production frontend',
            'abilities' => ['read', 'create'],
        ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'origin' => 'https://example.com',
            'description' => 'Production frontend',
            'abilities' => ['read', 'create'],
            'is_active' => true,
        ]);

    expect(database()->table('project_origins')->where([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
    ])->exists())->toBeTrue();
});

test('origin is normalized', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'HTTPS://EXAMPLE.COM/',
            'abilities' => ['read'],
        ]);

    $response->assertStatus(201);

    expect(database()->table('project_origins')->where([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
    ])->exists())->toBeTrue();
});

test('cannot add duplicate origin', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'https://example.com',
            'abilities' => ['read'],
        ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['message' => 'This origin is already in the whitelist.']);
});

test('can update origin configuration', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $origin = ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'description' => 'Old description',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('projects.settings.whitelist.update', [$project->id, $origin->id]), [
            'description' => 'New description',
            'abilities' => ['read', 'create', 'update'],
        ]);

    $response->assertStatus(200);

    expect(database()->table('project_origins')->where('id', $origin->id)->value('description'))->toBe('New description');

    $origin->refresh();
    expect($origin->abilities)->toBe(['read', 'create', 'update']);
});

test('can toggle origin active status', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $origin = ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.toggle', [$project->id, $origin->id]));

    $response->assertStatus(200)
        ->assertJsonFragment(['is_active' => false]);

    $origin->refresh();
    expect($origin->is_active)->toBeFalse();

    // Toggle back
    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.toggle', [$project->id, $origin->id]));

    $response->assertStatus(200)
        ->assertJsonFragment(['is_active' => true]);
});

test('can delete origin from whitelist', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $origin = ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
    ]);

    $response = $this->actingAs($user)
        ->deleteJson(route('projects.settings.whitelist.destroy', [$project->id, $origin->id]));

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Origin removed from whitelist.']);

    expect(database()->table('project_origins')->where('id', $origin->id)->exists())->toBeFalse();
});

test('only read create update abilities allowed', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'https://example.com',
            'abilities' => ['delete'], // Not allowed
        ]);

    $response->assertStatus(422);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'https://example.com',
            'abilities' => ['admin'], // Not allowed
        ]);

    $response->assertStatus(422);
});

test('origin must be valid url', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'not-a-url',
            'abilities' => ['read'],
        ]);

    $response->assertStatus(422);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'ftp://example.com', // Only http/https allowed
            'abilities' => ['read'],
        ]);

    $response->assertStatus(422);
});

test('api access with valid origin', function (): void {
    $project = Project::factory()->create();

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'is_active' => true,
    ]);

    // Create a collection to test read access
    $collection = $project->collections()->create([
        'name' => 'Posts',
        'slug' => 'posts',
    ]);

    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'https://example.com',
    ])->getJson('/api/' . $collection->slug);

    $response->assertStatus(200);
});

test('api access with inactive origin', function (): void {
    $project = Project::factory()->create();

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'is_active' => false, // Inactive
    ]);

    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'https://example.com',
    ])->getJson('/api');

    $response->assertStatus(401); // Should fail authentication
});

test('api access with unauthorized origin', function (): void {
    $project = Project::factory()->create();

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'],
        'is_active' => true,
    ]);

    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'https://evil.com', // Not in whitelist
    ])->getJson('/api');

    $response->assertStatus(401); // Should fail authentication
});

test('api access respects origin abilities', function (): void {
    $project = Project::factory()->create();

    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'https://example.com',
        'abilities' => ['read'], // Only read
        'is_active' => true,
    ]);

    $collection = $project->collections()->create([
        'name' => 'Posts',
        'slug' => 'posts',
    ]);

    // Read should work
    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'https://example.com',
    ])->getJson('/api/' . $collection->slug);

    $response->assertStatus(200);

    // Create should fail
    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'https://example.com',
    ])->postJson('/api/' . $collection->slug, [
        'data' => ['title' => 'Test'],
    ]);

    $response->assertStatus(403);
});

test('origin with port number', function (): void {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $project->members()->attach($user->id);
    grantWhitelistPermission($user);

    $response = $this->actingAs($user)
        ->postJson(route('projects.settings.whitelist.store', $project->id), [
            'origin' => 'http://localhost:3000',
            'abilities' => ['read'],
        ]);

    $response->assertStatus(201);

    expect(database()->table('project_origins')->where([
        'project_id' => $project->id,
        'origin' => 'http://localhost:3000',
    ])->exists())->toBeTrue();

    // Test API access with port
    ProjectOrigin::create([
        'project_id' => $project->id,
        'origin' => 'http://localhost:8080',
        'abilities' => ['read'],
        'is_active' => true,
    ]);

    $response = $this->withHeaders([
        'project-id' => $project->uuid,
        'Origin' => 'http://localhost:8080',
    ])->getJson('/api');

    $response->assertStatus(200);
});
