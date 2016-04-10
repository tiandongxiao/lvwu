<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WxMenuController extends Controller
{
    private $menu;

    /**
     * WxMenuController constructor.
     * @param $menu
     */
    public function __construct(Application $application)
    {
        $this->menu = $application->menu;
    }

    public function menu()
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "官网",
                "key"  => "GO_HOME_ADDRESS"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.exingdong.com"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://www.exingdong.com/wxpay/jsapi/test"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "GIVE_US_ZAN"
                    ],
                ],
            ],
        ];
        $this->menu->add($buttons);
    }
}
