<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Log;


class AuthWeChatController extends Controller
{
    private $wechat;

    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * 用户绑定微信入口，此方法只有auth用户可访问
     *
     * @return mixed
     */
    public function wxBind()
    {
        echo 'I am going to bind a wx account to a user';
        return  Socialite::driver('wechat')->redirect();
    }

    /**
     * 解除微信绑定
     *
     * @param Request $request
     */
    public function wxUnBind(Request $request)
    {
        $user = $request->user();
        $user->wx_id = null;
        return back()->withErrors('微信已解除绑定');
    }

    /**
     * 微信登陆页面,只有guest用户可访问
     *
     * @return mixed
     */
    public function wxLogin()
    {
        echo 'I am going to WeiChat Login page';
        return  Socialite::driver('wechat')->redirect();
    }

    /**
     * 微信登陆回调处理逻辑
     *
     * @return mixed
     */
    public function wxCallback()
    {
        $info = Socialite::driver('wechat')->user();

        if(Auth::check()){
            return $this->bindWxAccountToUser($info);
        }
        return $this->createOrLoginWxAccount($info);
    }


    /**
     * 用户账号与微信账号绑定逻辑（）
     *
     * @param $wx_info
     * @return mixed
     */
    private function bindWxAccountToUser($info)
    {
        $user = Auth::user();
        //1.1 如果用户不是用微信登陆的，那就为其绑定微信号
        if(!$user->wx_id){
            $result = User::where('wx_id',$info['id'])->first();
            if($result){
                return redirect('/')->withErrors('这个微信账号已被其他用户绑定，您不能绑定此微信账号');
            }
            $user->wx_id = $info['id'];
            $user->save();
            return redirect('/')->withErrors('完成微信账号绑定');
        }
        //1.2 用户之前已经用微信扫码登录
        return redirect('/')->withErrors('您已登录，无需重新扫码');
    }

    /**
     * 使用微信用户信息创建一个网站用户账号
     *
     * @param $info
     * @return mixed
     */
    private function createOrLoginWxAccount($info)
    {
        //2 如果用户没有登录
        $user = User::where('wx_id',$info['id'])->first();

        // 如果用户是不是已注册用户，需要创建新用户
        if(is_null($user)){
            $user = User::create([
                'wx_id' => $info['id'],
                'role'  => 'undefined',
            ]);
        }

        Auth::login($user);

        switch ($user->role){
            case 'lawyer':
            case 'assist':
                return redirect('/')->withErrors('欢迎'.$user->role.'使用我们的法律平台');
            case 'client':
                return redirect('/')->withErrors('欢迎咨询用户使用我们的服务');
            case 'undefined':
                return redirect('bind/chose');
            default:
                return redirect('/')->withErrors('您的信息已被记录，恶意攻击将被记录在案');
        }
    }

    public function serve()
    {
        $wechat = app('wechat');
        $userApi = $wechat->user;


        $wechat->server->setMessageHandler(function($message) use($userApi){
            
            switch ($message->MsgType) {
                case 'event':
                    # 事件消息...
                    break;
                case 'text':
                    return '你好! '.$userApi->get($message->FromUserName)->nickname;
                    break;
                case 'image':
                    # 图片消息...
                    break;
                case 'voice':
                    # 语音消息...
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    # 坐标消息...
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    # code...
                    break;
            }
        });

        return $wechat->server->serve();
    }

    public function users()
    {
        $users = $this->wechat->user->lists();
        dd($users);
    }

    public function user($open_id)
    {
        $user = $this->wechat->user->get($open_id);
        dd($user);
    }

    public function remark()
    {
        $this->wechat->user->remark('oKHQjuAhFF0ew7xpr_mLJjDLGYGc','绝代双骄');
        return 'OK';
    }
}

