<?php

namespace Database\Seeders;

use App\Enums\Categories;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

 
        foreach (Categories::getValues() as $category)
        {   
            $slug = \Str::slug($category);
            Category::updateOrCreate(['slug' => $slug], ['name' => $category, 'slug' => $slug]);
        }   
    }
}
