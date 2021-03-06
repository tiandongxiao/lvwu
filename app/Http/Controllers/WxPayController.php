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
    private $app;

    private $payment;
    private $user;


    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->payment = $this->app->payment;
        $this->user = $this->app->user;


    }
    public function payCallback()
    {
        $response = $this->payment->handleNotify(function($notify, $successful){
            #返回值中不包含transaction_id时，此时用户尚未生成支付订单
            Log::info('This is notify transaction id --'.$notify->transaction_id.'||'.$successful);
            return true;

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = 查询订单($notify->transaction_id);

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 'paid';
            } else { // 用户支付失败
                $order->status = 'paid_fail';
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成

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
        $order = new Order([
            'body'             => '服务费',
            'detail'           => Str::random(16),
            'out_trade_no'     => Str::random(16),
            'total_fee'        => random_int(10,1000),
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

    public function payJSTest($product_id)
    {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        $open_id = $user->getId();
        $info = $this->user->get($open_id);
        dd($info);
        dd($user);

        $order = new Order([
            'body'             => '服务费',
            'detail'           => Str::random(16),
            'out_trade_no'     => Str::random(16),
            'total_fee'        => random_int(10,1000),
            'trade_type'       =>  'JSAPI',
            'openid'          => $open_id
        ]);

        $result = $this->payment->prepare($order);
        $params = $this->payment->configForPayment($result->prepay_id);
        $price  = $order->total_fee;
        return view('payment.js',compact('params','price'));
    }
}
