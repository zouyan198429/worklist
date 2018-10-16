<?php

namespace App\Services;

/**
 *业务通用接口类
 */
class CommonBusiness
{

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param string $mainObj 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param obj $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)  格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersionApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $forceIncVersion= 1, $companyId = null , $notLog = 0){
        $url = config('public.apiUrl') . config('apiUrl.common.compareHistoryOrUpdateVersionApi');
        $requestData = [
            'mainObj' => $mainObj,
            'primaryVal' => $primaryVal,
            'historyObj' => $historyObj,
            'historyTable' => $HistoryTableName,
            'historySearch' => $historySearch,
            'ignoreFields' => $ignoreFields,
            'forceIncVersion' => $forceIncVersion ,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 根据主表id，获得对应的历史表id
     *
     * @param string $mainObj 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param obj $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  int 历史记录表id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryIdApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $companyId = null , $notLog = 0){
        $url = config('public.apiUrl') . config('apiUrl.common.getHistoryIdApi');
        $requestData = [
            'mainObj' => $mainObj,
            'primaryVal' => $primaryVal,
            'historyObj' => $historyObj,
            'historyTable' => $HistoryTableName,
            'historySearch' => $historySearch,
            'ignoreFields' => $ignoreFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param obj $mainObj 主表对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreateApi($mainObj, $searchConditon, $updateFields, $companyId = null , $notLog = 0){
        $url = config('public.apiUrl') . config('apiUrl.common.firstOrCreateApi');
        $requestData = [
            'mainObj' => $mainObj,
            'searchConditon' => $searchConditon,
            'updateFields' => $updateFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param obj $mainObj 主表对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreateApi($mainObj, $searchConditon, $updateFields, $companyId = null , $notLog = 0){
        $url = config('public.apiUrl') . config('apiUrl.common.updateOrCreateApi');
        $requestData = [
            'mainObj' => $mainObj,
            'searchConditon' => $searchConditon,
            'updateFields' => $updateFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

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
    public static function getinfoApi($modelName, $relations = '', $companyId = null , $id = null, $notLog = 0){
        $url = config('public.apiUrl') . config('apiUrl.common.getinfoApi');
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
            //throws('需要获取的记录id有误!', $this->source);
            throws('需要获取的记录id有误!');
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
    public static function getInfoByQuery($modelName, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];

        $resultDatas = self::ajaxGetList($modelName, $pageParams, $companyId,$queryParams ,$relations,$notLog);
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
    public static function ajaxDelApi($modelName, $companyId = null,$queryParams =''  , $notLog = 0){

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
        $url = config('public.apiUrl') . config('apiUrl.common.delApi');

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
    public static function ajaxGetAllList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
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
        $url = config('public.apiUrl') . config('apiUrl.common.getAllApi');
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
    public static function ajaxGetList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0)
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

        $url = config('public.apiUrl') . config('apiUrl.common.getlistApi');
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
    public static function createApi($modelName,$saveData= [], $companyId = null, $notLog = 0 )
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
        $url = config('public.apiUrl') . config('apiUrl.common.addnewApi');
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
    public static function createBathApi($modelName,$saveData= [], $companyId = null, $notLog = 0 )
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
        $url = config('public.apiUrl') . config('apiUrl.common.addnewBathApi');
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
    public static function createBathByPrimaryKeyApi($modelName,$saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 )
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
        $url = config('public.apiUrl') . config('apiUrl.common.addnewBathByIdApi');
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
    public static function ModifyByQueyApi($modelName, $saveData= [], $queryParams='', $companyId = null, $notLog = 0 ){

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
        $url = config('public.apiUrl') . config('apiUrl.common.saveApi');
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
    public static function saveBathById($modelName, $saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 ){

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
        $url = config('public.apiUrl') . config('apiUrl.common.saveBathById');
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
    public static function saveByIdApi($modelName, $id, $saveData, $companyId = null, $notLog = 0){
        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($saveData)){
            // throws('需要更新的数据不能为空!', $this->source);
            throws('需要更新的数据不能为空!');
        }
        $url = config('public.apiUrl') . config('apiUrl.common.saveByIdApi');
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
    public static function incDecByQueyApi($modelName, $queryParams='', $incDecType = 'dec', $incDecField = '', $incDecVal = 0, $saveData= [], $companyId = null, $notLog = 0 ){

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
        $url = config('public.apiUrl') . config('apiUrl.common.saveDecIncByQueryApi');
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
    public static function bathIncDecByArrApi($saveData= [], $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
        $url = config('public.apiUrl') . config('apiUrl.common.saveDecIncByArrApi');
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
    public static function saveSyncByIdApi($modelName, $id, $syncParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($syncParams)){
            // throws('需要更新的数据不能为空!', $this->source);
            throws('需要更新的数据不能为空!');
        }

        $url = config('public.apiUrl') . config('apiUrl.common.saveSyncByIdApi');
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
    public static function detachByIdApi($modelName, $id, $detachParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($detachParams)){
            // throws('需要移除的数据不能为空!', $this->source);
            throws('需要移除的数据不能为空!');
        }

        $url = config('public.apiUrl') . config('apiUrl.common.detachApi');
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

    /**
     * 根据资源id，删除资源及数据表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ResourceDelById($id, $companyId = null, $notLog = 0){
        $model_name = 'Resource';
        // 获得数据记录
        $relations = '';
        if(is_numeric($companyId) && $companyId > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $companyId,
            ];
            $info = CommonBusiness::judgePower($id, $judgeData, $model_name, $companyId, $relations, $notLog);
        }else{
            $info = self::getinfoApi($model_name, $relations, $companyId , $id, $notLog);
        }

        if(empty($info)){
            // throws('资源记录[' . $id . ']不存在!', $this->source);
            throws('资源记录[' . $id . ']不存在!');
        }
        // 删除文件
        self::resourceDelFile([$info]);
        //删除记录
        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
            ]
        ];
        return self::ajaxDelApi($model_name, $companyId , $queryParams, $notLog);

    }

    /**
     * 根据数据表记录，删除本地文件
     *
     * @param object $modelObj 当前模型对象
     * @param array $resources 资源记录数组 - 二维
     * @author zouyan(305463219@qq.com)
     */
    public static function resourceDelFile($resources = []){
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
    public static function resoursceUrl(&$reportsList, $type = 2){
        foreach($reportsList as $k=>$item){
            $reportsList[$k] = self::resourceUrl($item,$type);
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
    public static function resourceUrl(&$dataList,$type = 2){
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
    public static function getFormatResource($site_resources){
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
    public static function ajaxGetBaiDuData($requestData,$method)
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

    /**
     * 根据域名，作url跳转
     * @param string $httpHost 当前访问域名
     * @param int $operate 操作类型 1跳到主页,2跳到登陆页
     * @return  string 跳转url
     * @author zouyan(305463219@qq.com)
     */
    public static function urlRedirect($httpHost, $operate = 1){
        $domains = config('public.domains');
        $urls = $domains[$httpHost] ?? [];

        switch ($operate) {
            //1跳到主页
            case 1:
                return $urls['indexUrl'];
                break;
            //跳到登陆页
            case 2:
                return $urls['loginUrl'];
                break;
            default:
                echo 'error';
                die();
                // return redirect("/");
        }
    }


    // 判断权限-----开始
    // 判断权限 ,返回当前记录[可再进行其它判断], 有其它主字段的，可以重新此方法
    /**
     * 判断权限
     *
     * @param int $id id ,多个用,号分隔
     * @param array $judgeArr 需要判断的下标[字段名]及值 一维数组
     * @param string $model_name 模型名称
     * @param int $companyId 企业id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array 一维数组[单条] 二维数组[多条]
     * @author zouyan(305463219@qq.com)
     */
    public static function judgePower($id, $judgeArr = [] , $model_name = '', $company_id = '', $relations = '', $notLog  = 0){
        // $this->InitParams($request);
//        if(empty($model_name)){
//            $model_name = $this->model_name;
//        }
        $dataList = [];
        $isSingle = true;// 是否单条记录 true:是;false：否
        if (strpos($id, ',') === false) { // 单条
            // 获得当前记录
            $dataList[] =  self::getinfoApi($model_name, $relations, $company_id , $id, $notLog);
        }else{
            $isSingle = false;
            $queryParams =  [
                'where' => [
                    //['company_id', $company_id],
                    //['mobile', $keyword],
                ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//            ],
                // 'orderBy' => ['id'=>'desc'],
            ];
            if($company_id != ''){
                array_push($queryParams['where'],['company_id', $company_id]);
            }
            $queryParams['whereIn']['id'] = explode(',',$id);
            $dataList = self::ajaxGetAllList($model_name, [], $company_id,$queryParams ,$relations, $notLog );
        }
        foreach($dataList as $infoData){
            self::judgePowerByObj($infoData, $judgeArr);
        }
        return $isSingle ? $dataList[0] : $dataList;
    }

    public static function judgePowerByObj($infoData, $judgeArr = [] ){
        if(empty($infoData)){
            // throws('记录不存!', $this->source);
            throws('记录不存!');
        }
        foreach($judgeArr as $field => $val){
            if(!isset($infoData[$field])){
                // throws('字段[' . $field . ']不存在!', $this->source);
                throws('字段[' . $field . ']不存在!');
            }
            if( $infoData[$field] != $val ){
                // throws('没有操作此记录权限!信息字段[' . $field . ']', $this->source);
                throws('没有操作此记录权限!信息字段[' . $field . ']');
            }
        }
    }

    // 判断权限-----结束
}
