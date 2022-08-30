<?php

use Illuminate\Database\Seeder;
use App\Model\Dish;
use App\User;
use App\Model\Order;
use Faker\Generator as Faker;

class DishOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {

        $orders = Order::all();
        $users = User::all();

        foreach ($orders as $order) {
            $rand_id = random_int(1, count($users));
            $dishes = Dish::join('users', 'dishes.user_id', '=', 'users.id')
                ->select('dishes.id', 'users.name', 'dishes.user_id')
                ->where('users.id', $rand_id)
                ->limit(5)
                ->get();
            foreach ($dishes as $dish) {
                $quantity = $faker->numberBetween(1, 4);
                $order->dishes()->attach($dish, ['quantity' => $quantity]);
            }
        }
    }
}
