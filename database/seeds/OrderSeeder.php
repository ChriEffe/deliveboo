<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Model\Order;
use App\Model\Payment;
use Faker\Generator as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $addresses = [
            'Piazza Umberto 47',
            'Via Calefati 11',
            'Via Roma 44',
        ];

        for ($i = 0; $i < 75; $i++) {
            $random_keys = array_rand($addresses, 3);
            $newOrder = new Order();
            $newOrder->payment_id = Payment::inRandomOrder()->first()->id;
            $newOrder->name = $faker->firstName();
            $newOrder->lastname = $faker->lastName();
            $newOrder->email = $faker->email();
            $newOrder->address = $addresses[$random_keys[0]];
            $newOrder->date = Carbon::now()
                ->subDays(rand(1, 30))
                ->format('Y-m-d');
            $newOrder->time = $faker->time();
            $newOrder->price_total = 100;

            $newOrder->save();
        }
        // for ($i = 0; $i < 50; $i++) {
        //     $newOrder = new Order();
        //     $newOrder->payment_id = Payment::inRandomOrder()->first()->id;
        //     $newOrder->name = $faker->word();
        //     $newOrder->lastname = $faker->word();
        //     $newOrder->email = $faker->email();
        //     foreach ($addresses as $address) {
        //         $newOrder->address = $address;
        //     }
        //     $newOrder->date = $faker->date('Y_m_d');
        //     $newOrder->time = $faker->time();
        //     $newOrder->price_total = $faker->randomFloat(2, 5, 300);

        //     $newOrder->save();
        // }
    }
}
