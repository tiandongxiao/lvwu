<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Pingpp\Charge;

class OrdersController extends Controller
{

    public function __construct()
    {
        \Pingpp\Pingpp::setApiKey('sk_test_4mXrP0uvTe5On1KqbTLmzbDS');
    }
    public function pay(Request $request)
    {
        $charge = Charge::create([
            'order_no'  => time().rand(1000,99999),
            'amount'    => '100',
            'app'       => ['id' => 'app_unT0W5zjDiHSWfrz'],
            'channel'   => 'wx_pub_qr',
            'currency'  => 'cny',
            'client_ip' => $request->ip(),
            'subject'   => 'demo',
            'body'      => '1_32',
            'extra'     =>[
                'product_id' => 'Productid'
            ]
        ]);
        return view('payment.pay',compact('charge'));
    }
}
