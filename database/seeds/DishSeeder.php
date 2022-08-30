<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Model\Dish;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 0;

        foreach (config('dishes') as $dish) {
            $newDish = new Dish();
            $newDish->user_id = User::inRandomOrder()->first()->id;
            $newDish->name = $dish['name'];
            $newDish->slug = Str::slug("$newDish->name-$i", '-');
            $newDish->description = $dish['description'];
            $newDish->ingredients = $dish['ingredients'];
            $newDish->image = null;
            $newDish->price = $dish['price'];
            $newDish->availability = $dish['availability'];
            $newDish->course = $dish['course'];

            $newDish->save();

            $i++;
        }
        // foreach (config('dishes') as $dish) {
        //     $newDish = new Dish();
        //     $newDish->user_id = User::inRandomOrder()->first()->id;
        //     $newDish->name = $dish['name'];
        //     $newDish->slug = Str::slug("$newDish->name-$i", '-');
        //     $newDish->description = $dish['description'];
        //     $newDish->ingredients = $dish['ingredients'];
        //     $newDish->image = null;
        //     $newDish->price = $dish['price'];
        //     $newDish->availability = $dish['availability'];
        //     $newDish->course = $dish['course'];

        //     $newDish->save();

        //     $i++;
        // }
    }
}
