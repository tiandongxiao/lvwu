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
                $prepay_id = $result->prepay_id;
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
        Log::info('This is orderCallback function');
        $response = $this->payment->handleNotify(function($notify, $successful){

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            Log::info('This is notify transaction id --'.$notify->transaction_id);
//            $order = 查询订单($notify->transaction_id);
//
//            if (!$order) { // 如果订单不存在
//                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
//            }
//
//            // 如果订单存在
//            // 检查订单是否已经更新过支付状态
//            if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
//                return true; // 已经支付成功了就不再更新了
//            }
//
//            // 用户是否支付成功
//            if ($successful) {
//                // 不是已经支付状态则修改为已经支付状态
//                $order->paid_at = time(); // 更新支付时间为当前时间
//                $order->status = 'paid';
//            } else { // 用户支付失败
//                $order->status = 'paid_fail';
//            }
//
//            $order->save(); // 保存订单
//
//            return true; // 返回处理完成
        });

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
        $price = random_int(1,10);
//        $order = new Order([
//            'body'             => 'iPad mini 16G 白色',
//            'detail'           => 'iPad mini 16G 白色',
//            'out_trade_no'     => '1217752501201407033233368018',
//            'total_fee'        => random_int(1,10),
//            'notify_url'       => 'http://www.exingdong.com/wxpay/callback',
//            'trade_type'        =>  'NATIVE'
//        ]);
//
//        $result = $this->payment->prepare($order);
//        $prepayId = $result->prepay_id;
        $url = $this->payment->scheme($product_id);

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
