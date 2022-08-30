<?php

use Illuminate\Database\Seeder;
use App\Model\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('categories') as $category) {
            $newCategory = new Category();
            $newCategory->name = $category['name'];
            $newCategory->image = null;
            $newCategory->save();
        }
    }
}
