<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Animal::query()->delete();
        Animal::create(['name'=>'Maple','species'=>'dog','status'=>'available','priority'=>2]);
        Animal::create(['name'=>'Pixel','species'=>'cat','status'=>'hold','priority'=>3]);
        Animal::create(['name'=>'Juniper','species'=>'rabbit','status'=>'pending','priority'=>1]);
    }
}
