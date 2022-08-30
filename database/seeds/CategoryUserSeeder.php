<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Model\Category;

class CategoryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $categories = Category::inRandomOrder()
                ->limit(3)
                ->get();
            $user->categories()->attach($categories);
        }
    }
}
