<?php

use Illuminate\Database\Seeder;
use App\Model\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = [
            'Contanti alla consegna',
            'Carta di credito',
            'PayPal',
        ];

        foreach ($payments as $payment) {
            $newPayment = new Payment();
            $newPayment->type = $payment;

            $newPayment->save();
        }
    }
}
