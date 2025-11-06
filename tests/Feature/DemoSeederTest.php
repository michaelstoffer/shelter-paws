<?php

namespace Tests\Feature;

use App\Models\Animal;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_demo_data_without_errors(): void
    {
        $this->seed(DemoSeeder::class);

        $this->assertGreaterThan(0, Animal::count());
    }
}
