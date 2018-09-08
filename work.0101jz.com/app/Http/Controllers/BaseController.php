<?php

namespace App\Http\Controllers;

use App\Services\CommonBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $company_id = null ;
    protected $model_name = null;
    protected $user_info = [];
    protected $user_id = null;
    protected $source = -1;// 来源-1网站页面，2ajax；3小程序
    // 是否从小程序来的请求
    protected $redisKey = null;
    protected $save_session = true;// true后台来的，false小程序来的

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
    // 判断权限-----开始
    // 判断权限 ,返回当前记录[可再进行其它判断], 有其它主字段的，可以重新此方法
    /**
     * 判断权限
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param array $judgeArr 需要判断的下标[字段名]及值 一维数组
     * @param string $model_name 模型名称
     * @param int $companyId 企业id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function judgePower(Request $request, $id, $judgeArr = [] , $model_name = '', $company_id = '', $relations = '', $notLog  = 0){
        // $this->InitParams($request);
        if(empty($model_name)){
            $model_name = $this->model_name;
        }
        // 获得当前记录
        $infoData =  CommonBusiness::getinfoApi($model_name, $relations, $company_id , $id, $notLog);

        $this->judgePowerByObj($request, $infoData, $judgeArr);
        return $infoData;
    }

    public function judgePowerByObj(Request $request,$infoData, $judgeArr = [] ){
        if(empty($infoData)){
            throws('记录不存!', $this->source);
        }
        foreach($judgeArr as $field => $val){
            if(!isset($infoData[$field])){
                throws('字段[' . $field . ']不存在!', $this->source);
            }
            if( $infoData[$field] != $val ){
                throws('没有操作此记录权限!信息字段[' . $field . ']', $this->source);
            }
        }
    }

    // 判断权限-----结束
}
