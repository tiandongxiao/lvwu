<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use EasyWeChat\Support\XML;

class WxPayController extends Controller
{
    private $options = [
        // 前面的appid什么的也得保留哦
        'app_id' => 'wxece8375442c7704d',

        // payment
        'payment' => [
            'merchant_id'        => '1270537701',
            'key'                => 'sanmingzhi19811121tina19850811ov',
            'cert_path'          => 'http://www.exingdong.com/cer/wx/apiclient_cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'http://www.exingdong.com/cer/wx/apiclient_key.pem',      // XXX: 绝对路径！！！！
            'notify_url'         => 'http://www.exingdong.com/wxpay/callback',       // 你也可以在下单时单独设置来想覆盖它
        ],
    ];

    private $app = null;
    private $payment = null;


    public function __construct()
    {
        $this->app = new Application($this->options);
        $this->payment = $this->app->payment;

    }
    public function payCallback()
    {
        $response = $this->payment->handleNotify(function($notify, $successful){
            if(is_null($notify)){
                Log::error('Notify is empty');
            }
            #返回值中不包含transaction_id时，此时用户尚未生成支付订单
            if(!isset($notify->transaction_id)){
                Cache::add('notify',$notify,10);
                Log::info('This is user id --'.$notify->openid.'|| 产品id '.$notify->product_id.'|| '.$notify->prepay_id);
                $order = new Order([
                    'body'             => Str::random(16),
                    'detail'           => Str::random(16),
                    'out_trade_no'     => Str::random(16),
                    'total_fee'        => 1,
                    'trade_type'       =>  'NATIVE'
                ]);

                $result = $this->payment->prepare($order);
                Cache::forget('result');
                Cache::add('result',$result,10);
                return $result;

            }else{
                Log::info('This is notify transaction id --'.$notify->transaction_id.'||'.$successful);
            }
        });

        return $response;
    }


    public function orderCallback()
    {

    }

    public function newOrder()
    {
        $order = new Order([
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => '1217752501201407033233368018',
            'total_fee'        => 1,
            'notify_url'       => 'http://www.exingdong.com/wxpay/callback',
            'trade_type'        =>  'NATIVE'
        ]);
        $result = $this->payment->prepare($order);
        $prepayId = $result->prepay_id;
    }

    public function generateQrCode()
    {
        
    }

    public function buyProduct()
    {
        $product_id = 'thisisanewgood';
        $price = 1;


        $url = $this->payment->scheme('goodhappy');
        return view('payment.good',compact('url','price'));
    }

    public function payTest($product_id)
    {
//        $price = random_int(1,10);
//        $url = $this->payment->scheme($product_id);
//
//
//
//        return view('payment.good',compact('url','price'));

        $order = new Order([
            'body'             => '服务费',
            'detail'           => Str::random(16),
            'out_trade_no'     => Str::random(16),
            'total_fee'        => 1,
            'trade_type'       =>  'NATIVE'
        ]);

        $result = $this->payment->prepare($order);
        $price = $order->total_fee;
        $url = $result->code_url;

        return view('payment.good',compact('url','price'));
    }

    public function getCache()
    {
        dd(Cache::get('result'));
    }

    public function getNotify()
    {
        dd(Cache::get('notify'));
    }
}
