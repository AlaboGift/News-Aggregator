<?php

namespace Database\Seeders;

use App\Services\NewsService;
use Illuminate\Database\Seeder;

class AirticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new NewsService)->fetchNews();
    }
}
