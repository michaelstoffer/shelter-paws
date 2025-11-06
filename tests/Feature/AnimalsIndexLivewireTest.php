<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class AnimalsIndexLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_guests_to_login_for_adoption_queue(): void
    {
        $this->get('/adoption-queue')->assertRedirect('/login');
    }

    public function test_renders_adoption_queue_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        Animal::factory()->count(3)->create();

        $this->actingAs($user)
            ->get('/adoption-queue')
            ->assertInertia(fn (Assert $page) => $page
                ->component('AdoptionQueue')
                ->has('animals', 3)
                ->has('filters', fn (Assert $a) => $a->where('status', '')->etc())
            )->assertOk();
    }

    public function test_filters_adoption_queue_by_status(): void
    {
        $user = User::factory()->create();
        Animal::factory()->create(['status' => 'available']);
        Animal::factory()->create(['status' => 'hold']);
        Animal::factory()->create(['status' => 'pending']);

        $this->actingAs($user)
            ->get('/adoption-queue?status=hold')
            ->assertInertia(fn (Assert $page) => $page
                ->component('AdoptionQueue')
                ->has('animals', 1, fn (Assert $a) => $a->where('status', 'hold')->etc())
                ->where('filters.status', 'hold')
            )->assertOk();
    }

    public function test_updates_priority_within_valid_range(): void
    {
        $user = User::factory()->create();
        $animal = Animal::factory()->create(['priority' => 3]);

        $this->actingAs($user)
            ->patch("/animals/{$animal->id}/priority", ['priority' => 1])
            ->assertRedirect();

        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'priority' => 1,
        ]);
    }

    public function test_rejects_invalid_priority_updates(): void
    {
        $user = User::factory()->create();
        $animal = Animal::factory()->create(['priority' => 3]);

        $this->actingAs($user)
            ->patch("/animals/{$animal->id}/priority", ['priority' => 999])
            ->assertSessionHasErrors('priority');

        // unchanged
        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'priority' => 3,
        ]);
    }

    public function test_bulk_resets_priorities_all(): void
    {
        $user = User::factory()->create();
        $a = Animal::factory()->create(['priority' => 5]);
        $b = Animal::factory()->create(['priority' => 4]);

        $this->actingAs($user)
            ->patch('/adoption-queue/reset-priorities', ['to' => 2])
            ->assertRedirect();

        $this->assertDatabaseHas('animals', ['id' => $a->id, 'priority' => 2]);
        $this->assertDatabaseHas('animals', ['id' => $b->id, 'priority' => 2]);
    }

    public function test_bulk_resets_priorities_respecting_status_filter(): void
    {
        $user = User::factory()->create();
        $available = Animal::factory()->create(['status' => 'available', 'priority' => 5]);
        $hold      = Animal::factory()->create(['status' => 'hold',      'priority' => 4]);

        $this->actingAs($user)
            ->patch('/adoption-queue/reset-priorities', ['to' => 1, 'status' => 'available'])
            ->assertRedirect();

        $this->assertDatabaseHas('animals', ['id' => $available->id, 'priority' => 1]);
        $this->assertDatabaseHas('animals', ['id' => $hold->id, 'priority' => 4]); // unchanged
    }
}
