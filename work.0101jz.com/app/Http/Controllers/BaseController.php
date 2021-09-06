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
    public $operate_staff_id = 0; // 操作人员id
    public $operate_staff_history_id = 0;// 操作人员历史id
    public $source = -1;// 来源-1网站页面，2ajax；3小程序
    // 是否从小程序来的请求
    public $redisKey = null;
    public $save_session = true;// true后台来的，false小程序来的
    // 返回到前端的数据

    public $reDataArr = [
        'real_name'=> '',
    ];


    //缓存
    public $cache_sel = 1 + 2;//是否强制不缓存 1:缓存读,读到则直接返回;2缓存数据


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

    /**
     * 获得缓存数据
     *
     * @param string $pre 键前缀 __FUNCTION__
     * @param string $cacheKey 键
     * @param array $paramKeyValArr 会作为键的关键参数值数组 --一维数组
     * @param int 选填 $operate 操作 1 转为json 2 序列化 ;
     * @param keyPush 键加入无素 1 $pre 键前缀 2 当前控制器方法名;
     * @return mixed  ; false失败
     * @author zouyan(305463219@qq.com)
     */
    public function getCacheData($pre, &$cacheKey, $paramKeyValArr, $operate = 1, $keyPush = 0){
        return Tool::getCacheData($pre, $cacheKey, $paramKeyValArr, $operate, $keyPush);
    }

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $cacheData 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $key
     * @author zouyan(305463219@qq.com)
     */
    public function setCacheData($pre, $cacheKey, $cacheData, $expire = 60, $operate =1){
        // 缓存数据
        return Tool::cacheData($pre, $cacheKey, $cacheData, $expire, $operate); // 1分钟
    }

    /**
     * 保存redis值-json/序列化保存
     * @param array $userInfo 用户信息数组；注意必须有  staff_company  企业信息-- 一维数组
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public function initCompanyMsg($userInfo){
        $company_id = $userInfo['staff_company']['id'] ?? 0;// 企业id
        $this->reDataArr['baseArr']['company_id'] = $company_id;
        $company_name = $userInfo['staff_company']['company_name'] ?? '';// 企业名称
        $this->reDataArr['baseArr']['company_name'] = $company_name;

        $module_no = $userInfo['staff_company']['module_no'] ?? 0;// 开通模块编号
        $this->reDataArr['baseArr']['module_no'] = $module_no;
        $module_no_text = $userInfo['staff_company']['module_no_text'] ?? '';// 开通模块编号名称
        $this->reDataArr['baseArr']['module_no_text'] = $module_no_text;

        $send_work_department_id = $userInfo['staff_company']['send_work_department_id'] ?? 0;// 接线部门id
        $this->reDataArr['baseArr']['send_work_department_id'] = $send_work_department_id;
    }

    /** 使用

    // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
    if( ($this->cache_sel & 1) == 1){
        $cachePre = __FUNCTION__;// 缓存前缀
        $cacheKey = '';// 缓存键[没算前缀]
        $paramKeyValArr = $request->input();//[$company_id, $operate_no];// 关键参数  $request->input()
        $cacheResult =$this->getCacheData($cachePre,$cacheKey, $paramKeyValArr , 1, 1 + 2);
        if($cacheResult !== false) return $cacheResult;
    }
     *
     *
     *
    // 缓存数据
    if( ($this->cache_sel & 2) == 2) {
        $this->setCacheData($cachePre, $cacheKey, $resultData, 60, 1);
    }
     */
}
