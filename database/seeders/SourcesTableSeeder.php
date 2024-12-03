<?php

namespace Database\Seeders;

use App\Enums\Sources;
use App\Models\Source;
use Illuminate\Database\Seeder;

class SourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 
        foreach (Sources::getValues() as $source)
        {   
            $slug = \Str::slug($source);
            Source::updateOrCreate(['slug' => $slug], ['name' => $source, 'slug' => $slug]);
        }   
    }
}
