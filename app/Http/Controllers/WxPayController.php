<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        dd('I am callback');
    }

    public function newOrder()
    {
        
    }

    public function generateQrCode()
    {
        
    }
}
