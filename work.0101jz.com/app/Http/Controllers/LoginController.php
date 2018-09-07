<?php

namespace App\Http\Controllers;

use App\Services\Common;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // 是否从小程序来的请求
    protected $redisKey = null;
    protected $save_session = true;// true后台来的，false小程序来的

    protected $company_id = null ;
    protected $model_name = null;
    protected $user_info = [];
    protected $user_id = null;
    protected $source = -1;// 来源-1网站页面，2ajax；3小程序

    public function InitParams(Request $request)
    {
        // 获得redisKey 参数值
        $temRedisKey = Common::get($request, 'redisKey');
        if(isAjax()){
            $this->source = 2;
        }
        if(!empty($temRedisKey)){// 不为空，则是从小程序来的
            $this->redisKey = $temRedisKey;
            $this->save_session = false;
            $this->source = 3;
        }
        //session_start(); // 初始化session
        //$userInfo = $_SESSION['userInfo']?? [];
        $userInfo = $this->getUserInfo();
        // pr($userInfo);
        if(empty($userInfo)) {
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $userInfo['company_id'] ?? null;//Common::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        // Common::judgeInitParams($request, 'company_id', $company_id);
        $this->user_info =$userInfo;
        $this->user_id = $userInfo['id'] ?? '';
        $this->company_id = $company_id;
        // 每*分钟，自动更新一下左则
        $recordTime  = time();
        $difTime = 60 * 5 ;// 5分钟
        $modifyTime = $userInfo['modifyTime'] ?? ($recordTime - $difTime - 1);
        if($this->save_session &&  ($modifyTime + $difTime) <=  $recordTime){// 后台
            $proUnits = $this->getUnits($this->user_info);
            $userInfo['proUnits'] = $proUnits;
            $userInfo['modifyTime'] = time();
            $redisKey = $this->setUserInfo($userInfo, -1);
        }
    }

    // 登陆信息
    // 获得生产单元信息
    public function getUnits($user_info = []){
        $proUnits = [];
        // $user_info = $this->user_info;
        $user_id = $user_info['id'] ?? 0;
        $company_id = $user_info['company_info']['id'] ?? 0;//$this->company_id;
        // 判断是否在VIP有效期内-- 没有有效期，则处理[重新登录]
        $company_vipbegin = $user_info['company_info']['company_vipbegin'] ?? '';
        $company_vipend = $user_info['company_info']['company_vipend'] ?? '';
        //判断开始
        $comp_begin_time_unix = judgeDate($company_vipbegin);
        if($comp_begin_time_unix === false){
            // ajaxDataArr(0, null, 'VIP开始日期不是有效日期');
            // 删除登陆状态
            $resDel = $this->delUserInfo();
            return $proUnits;
        }

        //判断期限结束
        $comp_end_time_unix = judgeDate($company_vipend);
        if($comp_end_time_unix === false){
            // ajaxDataArr(0, null, 'VIP结束日期不是有效日期');
            // 删除登陆状态
            $resDel = $this->delUserInfo();
            return $proUnits;
        }

        if($comp_end_time_unix < $comp_begin_time_unix){
            // ajaxDataArr(0, null, 'VIP结束日期不能小于开始日期');
            // 删除登陆状态
            //$resDel = $this->delUserInfo();
            //return $proUnits;
        }
        $nowTime = time();
        if($nowTime < $comp_begin_time_unix){
            // ajaxDataArr(0, null, 'VIP还未到开始日期，不能新加生产单元!');
            // 删除登陆状态
            //$resDel = $this->delUserInfo();
            //return $proUnits;
        }
        if($nowTime > $comp_end_time_unix){
            // ajaxDataArr(0, null, 'VIP已过期，不能新加生产单元!');
            // 删除登陆状态
            //$resDel = $this->delUserInfo();
            // return $proUnits;
        }

        // 判断用户状态
        $relations = "";
        $userInfo = $this->getinfoApi('CompanyAccounts', $relations, 0 , $user_id,1);
        $account_status = $userInfo['account_status'] ?? 1;
        if($account_status != 0){
            // 删除登陆状态
            $resDel = $this->delUserInfo();
            return $proUnits;
        }
        // 获得当前所有的
        //$relations = '';// 关系
        //if(!$this->save_session){
            $relations =['siteResources'];
        //}
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
            ],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        $proUnitList = $this->ajaxGetAllList('CompanyProUnit', '', $company_id,$queryParams ,$relations );

        foreach($proUnitList as $v){
            $status = $v['status'] ?? 0;
            if($this->save_session && (! in_array($status,[1]))){//后台
                continue;
            }elseif( (! $this->save_session) && (! in_array($status,[1]))){// 小程序[0,1]
                continue;
            }
            $begin_time = $v['begin_time'] ?? '';
            $end_time = $v['end_time'] ?? '';
            //判断开始
            $begin_time_unix = judgeDate($begin_time);
            if($begin_time_unix === false){
                continue;
                // ajaxDataArr(0, null, '开如日期不是有效日期');
            }

            //判断期限结束
            $end_time_unix = judgeDate($end_time);
 //           if($end_time_unix === false){
 //               continue;
                // ajaxDataArr(0, null, '结束日期不是有效日期');
 //           }

            if( $end_time_unix !== false && $end_time_unix < $begin_time_unix){
                continue;
                // ajaxDataArr(0, null, '结束日期不能小于开始日期');
            }
            $time = time();
            if($end_time_unix !== false && $end_time_unix < $time ){// 过期
                continue;
            }

            $tem = [
                'unit_id' => $v['id'],
                'site_pro_unit_id' => $v['site_pro_unit_id'],
                'pro_input_name' => $v['pro_input_name'],
                'status' => $v['status'],
                'status_text' => $v['status_text'],
                'begin_time' => judge_date($v['begin_time'],'Y-m-d'),
                'end_time' => judge_date($v['end_time'],'Y-m-d'),
            ];

            //if(! $this->save_session) {
                // $resource_url = $v['company_pro_config']['site_resources'][0]['resource_url'] ?? '';
                $resource_url = $v['site_resources'][0]['resource_url'] ?? '';
                $tem['resource_url'] = $resource_url;
                $this->resourceUrl($tem, 1);
            //}
            $proUnits[$v['id']] = $tem;
        }
        return $proUnits;
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
     * 根据model的id获得详情记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param int $id id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function getinfoApi($modelName, $relations = '', $companyId = null , $id = null, $notLog = 0){
        $url = config('public.apiUrl') . config('public.apiPath.getinfoApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if(empty($id) || (!is_numeric($id))){
            throws('需要获取的记录id有误!', $this->source);
        }
        //if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;
        //}
        if (!empty($relations)) {
            $requestData['relations'] = $relations ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 根据model的条件获得一条详情记录 - 一维
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function getInfoByQuery($modelName, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];

        $resultDatas = $this->ajaxGetList($modelName, $pageParams, $companyId,$queryParams ,$relations,$notLog);
        $dataList = $resultDatas['dataList'] ?? [];
        return $dataList[0] ?? [];
    }

    /**
     * ajax指定条件删除记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ajaxDelApi($modelName, $companyId = null,$queryParams =''  , $notLog = 0){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' =>$queryParams,//[// 查询条件参数
            //    'where' => [
            //        ['id', $id],
            //        ['company_id', $this->company_id]
            //    ]
            //],
        ];
        //$where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
           // array_push($where,['company_id', $companyId]) ;
        }
//        if (is_numeric($id) && $id > 0) {
//            array_push($where,['id', $id]) ;
//        }
//        if(!empty($where)){
//            $requestData['queryParams']['where'] = $where ;
//        }
        $url = config('public.apiUrl') . config('public.apiPath.delApi');

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * ajax获得列表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param array $pageParams
     * [
        'page' => $page,
        'pagesize' => $pagesize,
        'total' => $total,
        ]
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ajaxGetAllList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $requestData = [
            // 'company_id' => $companyId,
            'Model_name' => $modelName, // 模型
            'queryParams' => $queryParams, // 查询条件参数
            'relations' => $relations, // 查询关系参数
            'not_log' => $notLog,
        ];
        //$where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
         //   array_push($where,['company_id', $companyId]) ;
        }
//        if(!empty($where)){
//            if(!is_array($queryParams)){
//                $queryParams = [];
//            }
//            if(!isset($queryParams['where'])){
//                $queryParams['where'] = [];
//            }
//            if(isset($queryParams['where']) && empty($queryParams['where'])) {
//                $queryParams['where'] = [];
//            }
//            $queryParams['where'] = array_merge($queryParams['where'],$where);
//            $requestData['queryParams'] = $queryParams ;
//        }

        // $requestData = array_merge($requestData,$pageParams);
        $url = config('public.apiUrl') . config('public.apiPath.getAllApi');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * ajax获得列表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param array $pageParams
     * [
    'page' => $page,
    'pagesize' => $pagesize,
    'total' => $total,
    ]
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ajaxGetList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0)
    {
        $requestData = [
            // 'company_id' => $companyId,
            'Model_name' => $modelName, // 模型
            'queryParams' => $queryParams, // 查询条件参数
            'relations' => $relations, // 查询关系参数
            'not_log' => $notLog,
        ];
       // $where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
           // array_push($where,['company_id', $companyId]) ;
        }
//        if(!empty($where)){
//            if(!is_array($queryParams)){
//                $queryParams = [];
//            }
//            if(!isset($queryParams['where'])){
//                $queryParams['where'] = [];
//            }
//            if(isset($queryParams['where']) && empty($queryParams['where'])) {
//                $queryParams['where'] = [];
//            }
//            $queryParams['where'] = array_merge($queryParams['where'],$where);
//            $requestData['queryParams'] = $queryParams ;
//        }

        $requestData = array_merge($requestData,$pageParams);

        $url = config('public.apiUrl') . config('public.apiPath.getlistApi');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 修改或新加记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function createApi($modelName,$saveData= [], $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 新加用户
        $url = config('public.apiUrl') . config('public.apiPath.addnewApi');
        // 生成带参数的测试get请求
       // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 批量新加-data只能返回成功true:失败:false
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组-二维数组
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function createBathApi($modelName,$saveData= [], $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 新加用户
        $url = config('public.apiUrl') . config('public.apiPath.addnewBathApi');
        // 生成带参数的测试get请求
        // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 批量新加-data返回成功的id数组
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组-二维数组
     * @param string $primaryKey 表的主键字段名称
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function createBathByPrimaryKeyApi($modelName,$saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        if(!empty($primaryKey)){
            $requestData['primaryKey'] = $primaryKey;
        }
        // 新加用户
        $url = config('public.apiUrl') . config('public.apiPath.addnewBathByIdApi');
        // 生成带参数的测试get请求
        // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 根据条件修改记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param string $queryParams 条件数组/json字符
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ModifyByQueyApi($modelName, $saveData= [], $queryParams='', $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' => $queryParams, // 查询条件参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
        $url = config('public.apiUrl') . config('public.apiPath.saveApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 根据主健批量修改记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param string $queryParams 条件数组/json字符
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function saveBathById($modelName, $saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'primaryKey' => $primaryKey, // 记录主键
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
        $url = config('public.apiUrl') . config('public.apiPath.saveBathById');
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 通过id修改接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function saveByIdApi($modelName, $id, $saveData, $companyId = null, $notLog = 0){
        if(empty($id) ){
            throws('需要更新的记录id不能为空!', $this->source);
        }
        if(empty($saveData)){
            throws('需要更新的数据不能为空!', $this->source);
        }
        $url = config('public.apiUrl') . config('public.apiPath.saveByIdApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['dataParams'] = $saveData; // 需要更新的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param object $modelObj 当前模型对象
     * @param string $queryParams 条件数组/json字符
     * @param string incDecType 增减类型 inc 增 ;dec 减[默认]
     * @param string incDecField 增减字段
     * @param string incDecVal 增减值
     * @param array $saveData 要保存或修改的数组  修改的其它字段 -没有，则传空数组[]
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function incDecByQueyApi($modelName, $queryParams='', $incDecType = 'dec', $incDecField = '', $incDecVal = 0, $saveData= [], $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' => $queryParams, // 查询条件参数
            'incDecType' => $incDecType, // 增减类型 inc 增 ;dec 减[默认]
            'incDecField' => $incDecField, // 增减字段
            'incDecVal' => $incDecVal, // 增减值
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
        $url = config('public.apiUrl') . config('public.apiPath.saveDecIncByQueryApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param array $saveData 要保存或修改的数组
        $saveData = [
            [
                'Model_name' => 'model名称',
                'primaryVal' => '主键字段值',
                'incDecType' => '增减类型 inc 增 ;dec 减[默认]',
                'incDecField' => '增减字段',
                'incDecVal' => '增减值',
                'modifFields' => '修改的其它字段 -没有，则传空数组',
            ],
        ];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function bathIncDecByArrApi($saveData= [], $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
        $url = config('public.apiUrl') . config('public.apiPath.saveDecIncByArrApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 通过id同步修改关系接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $syncParams 要保存或修改的关系数组;可多个 ;格式 [ '关系方法名' =>关系值及相关字段]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function saveSyncByIdApi($modelName, $id, $syncParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            throws('需要更新的记录id不能为空!', $this->source);
        }
        if(empty($syncParams)){
            throws('需要更新的数据不能为空!', $this->source);
        }

        $url = config('public.apiUrl') . config('public.apiPath.saveSyncByIdApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['synces'] = $syncParams; // 需要更新的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;

        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 通过id移除关系接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $detachParams 要移除的关系数组;可多个 ;格式 [ '关系方法名' =>关系id或空(全部移除)]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function detachByIdApi($modelName, $id, $detachParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            throws('需要更新的记录id不能为空!', $this->source);
        }
        if(empty($detachParams)){
            throws('需要移除的数据不能为空!', $this->source);
        }

        $url = config('public.apiUrl') . config('public.apiPath.detachApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['detaches'] = $detachParams; // 需要移除的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;

        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

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
        $infoData = $this->getinfoApi($model_name, $relations, $company_id , $id, $notLog);
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
    /**
     * 根据资源id，删除资源及数据表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ResourceDelById($id, $companyId = null,$notLog = 0){
        $model_name = 'Resource';
        // 获得数据记录
        $relations = '';
        $info = $this->getinfoApi($model_name, $relations, $companyId , $id, $notLog);
        if(empty($info)){
            throws('资源记录[' . $id . ']不存在!', $this->source);
        }
        // 删除文件
        $this->resourceDelFile([$info]);
        //删除记录
        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
            ]
        ];
        return $this->ajaxDelApi($model_name, $companyId , $queryParams, $notLog);
    }

    /**
     * 根据数据表记录，删除本地文件
     *
     * @param object $modelObj 当前模型对象
     * @param array $resources 资源记录数组 - 二维
     * @author zouyan(305463219@qq.com)
     */
    public function resourceDelFile($resources = []){
        foreach($resources as $resource){
            $resource_url = $resource['resource_url'] ?? '';
            if(empty($resource_url)){
                continue;
            }
            @unlink(public_path($resource_url));// 删除文件
        }
    }

    /**
     * 根据数据表记录[二维]，转换资源url为可以访问的地址
     *
     * @param array $reportsList 栏目记录数组 - 二维
     * @param int $type 多少维  1:一维[默认]；2 二维 --注意是资源的维度
     * @author zouyan(305463219@qq.com)
     */
    public function resoursceUrl(&$reportsList, $type = 2){
        foreach($reportsList as $k=>$item){
            $reportsList[$k] = $this->resourceUrl($item,$type);
        }
        return $reportsList;
    }

    /**
     * 根据数据表记录，转换资源url为可以访问的地址
     *
     * @param array $dataList 资源记录数组 - 二维 / 一维
     * @param int $type 多少维  1:一维[默认]；2 二维 --注意是资源的维度
     * @author zouyan(305463219@qq.com)
     */
    public function resourceUrl(&$dataList,$type = 2){
        if($type == 2){
            if(isset($dataList['site_resources'])){
                $site_resources = $dataList['site_resources'] ?? [];
                foreach($site_resources as $k=>$site_resource){
                    $site_resources[$k]['resource_url'] = url($site_resource['resource_url']);
                }
                $dataList['site_resources'] = $site_resources;
            }
        }else{
            if(isset($dataList['resource_url'])){
                $dataList['resource_url'] = url($dataList['resource_url']);
            }
        }
        return $dataList;
    }
    /**
     * 根据site_resources记录，转换小程序的图片列数组-二维
     *
     * @param array $site_resources 资源记录数组 - 二维
     * @return  array $upload_picture_list 小程序的图片列数组-二维
     * @author zouyan(305463219@qq.com)
     */
    public function getFormatResource($site_resources){
        $upload_picture_list = [];
        // $site_resources = $infoData['site_resources'] ?? [];
        foreach($site_resources as $v){
            $upload_picture_list[] = [
                'upload_percent' => 100,
                'path' => $v['resource_url'] ?? '',
                'path_server' => $v['resource_url'] ?? '',
                'resource_id' => $v['id'] ?? 0,
            ];
        }
        //$infoData['upload_picture_list'] = $upload_picture_list;
        return $upload_picture_list;
    }


    /**
     * 获得百度数据
     *
     * @param array $requestData 请求参数数组

     * @param string $method 请求方法前缀
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public function ajaxGetBaiDuData($requestData,$method)
    {
        $requestData['ak'] = config('public.BaiDuAK');
        $requestData['output'] = 'json';
        $url = config('public.apiUrlBaiDu') . config('public.apiPathBaiDu.' . $method);
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        //return HttpRequest::HttpRequestApi($url, [], $requestData, 'GET');

        $result = HttpRequest::sendHttpRequest($url, [], $requestData, 'GET');

        $resultData = json_decode($result, true);
        $error = $resultData['error'] ?? 0;
        $status = $resultData['status'] ?? '返回数据错误!';
        $data = $resultData['results'] ?? [];
        if ($error != 0){
            // throws('百度接口错误:' . $status);
        }
        if(!is_array($data)){
            $data = [];
        }
        return $data;
    }
}
