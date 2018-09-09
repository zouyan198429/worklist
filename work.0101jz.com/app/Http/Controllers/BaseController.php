<?php

namespace App\Http\Controllers;

use App\Services\CommonBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $company_id = null ;
    public $model_name = null;
    public $user_info = [];
    public $user_id = null;
    public $source = -1;// 来源-1网站页面，2ajax；3小程序
    // 是否从小程序来的请求
    public $redisKey = null;
    public $save_session = true;// true后台来的，false小程序来的
    // 返回到前端的数据
    public $reDataArr = [
        'real_name'=>'',
    ];

    public function InitParams(Request $request)
    {

    }

    // 获取
    public function getUserInfo(){

        return Tool::getSession($this->redisKey, $this->save_session,
            config('public.sessionKey'), config('public.sessionRedisTye'));
    }
    // 保存
    public function setUserInfo($userInfo = '',$preKey = -1){
        //$preKey 为 -1,则根据 $this->save_session 来处理
        if($preKey == -1){
            $pre = config('public.sessionValPre') . ((int) $this->save_session ) . '_';
        }else{
            $pre = config('public.sessionValPre') . ((int) $preKey ) . '_';
        }
        $redisKey = Tool::setLoginSession($pre, $userInfo,
            $this->save_session, config('public.sessionKey'),
            config('public.sessionExpire'), config('public.sessionRedisTye'));
        return $redisKey;
    }
    // 删除
    public function delUserInfo(){
        return Tool::delSession($this->redisKey, $this->save_session, config('public.sessionKey'));
    }

    // 公共方法
}
