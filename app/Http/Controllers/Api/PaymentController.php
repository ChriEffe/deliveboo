<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PaymentRequest;
use Braintree\Gateway;
use App\Model\Dish;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function generate(PaymentRequest $request, Gateway $gateway) {
        
        $token = $gateway->clientToken()->generate();

        $data = [
            'success' => true,
            'token' => $token
        ];
        //dd($gateway->clientToken()->generate());

        return response()->json($data, 200);
    }

    public function makePayment(PaymentRequest $request, Gateway $gateway) {

        $dish = Dish::find($request->dish);

        $result = $gateway->transaction()->sale([
            'amount' => $dish->price,
            'paymentMethodNonce' => $request->token,
            // 'options' => [
            //     'submitForSettlment' => true
            // ]
        ]);

        if($result->success){
            $data = [
                'success' => true,
                'message' => 'Transazione eseguita con successo!',
            ];

            return response()->json($data, 200);
        }
        else {
            $data = [
                'success' => false,
                'message' => 'Transazione Fallita'
            ];
            return response()->json($data, 404);
        }   
    }
}