<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Subscriber::factory(10)->create();
        Field::factory(10)->create();
    }
}
