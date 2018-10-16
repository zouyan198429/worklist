<?php

namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class BaseBusiness
{

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
        $result = [
            'data_list'=>$resultDatas,//array(),//数据二维数组
            'total'=>$total,//总记录数 0:每次都会重新获取总数 ;$total :则>0总数据不会重新获取[除第一页]
            'page'=> $page,// 当前页
            'pagesize'=> $pagesize,// 每页显示的数量
            'totalPage'=> $totalPage,// 总页数
            //  'pageInfo' => showPage($totalPage,$page,$total,12,1),
        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function getBaseListData(Request $request, Controller $controller, $model_name, $queryParams = '',$relations = '', $oprateBit = 2 + 4,  $notLog = 0){
        $company_id = $controller->company_id;
        // 获得翻页的三个关键参数
        $pageParams = Common::getPageParams($request);
        // 关键字

        list($page, $pagesize, $total) = array_values($pageParams);
        /*
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','real_name'
//            ],
            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
        ];// 查询条件参数
        $relations = ['CompanyInfo'];// 关系
        */
        $result = [];
        if(  ($oprateBit & 2) == 2 ){ //2 分页获取[同时有1和2，2优先]；
            $result = CommonBusiness::ajaxGetList($model_name, $pageParams, $company_id,$queryParams ,$relations, $notLog);
        }else if(  ($oprateBit & 1) == 1 ){ //1:获得所有的;
            $result = CommonBusiness::ajaxGetAllList($model_name, $pageParams, $company_id,$queryParams ,$relations, $notLog );
        }
        if(isset($result['dataList'])){
            $resultDatas = $result['dataList'];
            $pagesize = $result['pageSize'] ?? $pagesize;
            $page = $result['page'] ?? $page;

            if ($total <= 0 ) {
                $total = $result['total'] ?? $total;
            }

            // $totalPage = $result['totalPage'] ?? 0;
        }else{
            $resultDatas = $result;
            //if ($total <= 0 ) {
            if(is_array($resultDatas)){
                $total = count($resultDatas);
            }elseif(is_numeric($resultDatas)){
                $total = $resultDatas;
                $resultDatas = [];
            }else{
                $resultDatas = [];
            }
            //}
            if($total > 0) $pagesize = $total;
        }
        // 处理图片地址
        // CommonBusiness::resoursceUrl($resultDatas);
        $totalPage = ceil($total/$pagesize);

//        $data_list = [];
//        foreach($resultDatas as $k => $v){
////            // 部门名称
////            $resultDatas[$k]['department_name'] = $v['staff_department']['department_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_department'])) unset($resultDatas[$k]['staff_department']);
////            // 小组名称
////            $resultDatas[$k]['group_name'] = $v['staff_group']['department_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_group'])) unset($resultDatas[$k]['staff_group']);
////            // 职位
////            $resultDatas[$k]['position_name'] = $v['staff_position']['position_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_position'])) unset($resultDatas[$k]['staff_position']);
//
//            $data_list[] = [
//                'id' => $v['id'] ,
//                'company_id' => $v['company_id'] ,
//                'company_name' => $v['company_info']['company_name'] ?? '',//  企业名称
//                //'resource_url' => $v['site_resources'][0]['resource_url'] ?? '' ,
//                //'resource_name' => $v['site_resources'][0]['resource_name'] ?? '' ,
//                'type_name' => $v['type_name'] ,
//                'created_at' => $v['created_at'],
//            ];
//        }
        $result = [
            'has_page'=> $totalPage > $page,
            'data_list'=>$resultDatas,//array(),//数据二维数组
            'total'=>$total,//总记录数 0:每次都会重新获取总数 ;$total :则>0总数据不会重新获取[除第一页]
            'page'=> $page,// 当前页
            'pagesize'=> $pagesize,// 每页显示的数量
            'totalPage'=> $totalPage,// 总页数
             'pageInfo' => "",//showPage($totalPage,$page,$total,12,1),
        ];
        if(  ($oprateBit & 4) == 4 ){
            $result['pageInfo'] = showPage($totalPage,$page,$total,12,1);
        }
        return $result;

    }

    /**
     * 删除单条数据--兼容批量删除
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param int $notLog 是否需要登陆 0需要1不需要 2已经判断权限，不用判断权限
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjaxBase(Request $request, Controller $controller, $model_name, $notLog = 0){

        $id = Common::get($request, 'id');
        Tool::dataValid([["input"=>$id,"require"=>"true","validator"=>"","message"=>'参数id值不能为空']]);

        $company_id = $controller->company_id;

        // 判断权限
        if(($notLog & 2) == 2 ) {
            $notLog = $notLog - 2 ;
        }else{
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);
        }

        $queryParams =[// 查询条件参数
            'where' => [
                // ['id', $id],
                ['company_id', $company_id]
            ]
        ];
        if (strpos($id, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $id]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$id);
        }

        $resultDatas = CommonBusiness::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);

        return ajaxDataArr(1, $resultDatas, '');
    }


    /**
     * 删除单条数据---总系统类表--兼容批量删除
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delSysAjaxBase(Request $request, Controller $controller, $model_name, $notLog = 0){

        $id = Common::getInt($request, 'id');
        $company_id = $controller->company_id;

        // 判断权限
//        $judgeData = [
//            'company_id' => $company_id,
//        ];
//        $relations = '';
//        CommonBusiness::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);

        $queryParams =[// 查询条件参数
            'where' => [
//                ['id', $id],
//                ['company_id', $company_id]
            ]
        ];
        if (strpos($id, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $id]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$id);
        }
        $resultDatas = CommonBusiness::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);

        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param int $id id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function getInfoDataBase(Request $request, Controller $controller, $model_name, $id, $relations = '', $notLog = 0){
//        $company_id = $controller->company_id;
//        // $relations = '';
//        return CommonBusiness::getinfoApi($model_name, $relations, $company_id , $id, $notLog);
//    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceByIdBase(Request $request, Controller $controller, $model_name, $saveData, &$id, $notLog = 0){
        $company_id = $controller->company_id;
        if($id <= 0){// 新加
            $resultDatas = CommonBusiness::createApi($model_name, $saveData, $company_id, $notLog);
            $id = $resultDatas['id'] ?? 0;
        }else{// 修改
            $resultDatas = CommonBusiness::saveByIdApi($model_name, $id, $saveData, $company_id, $notLog);
        }
        return $resultDatas;
    }

    /**
     * 通过id同步修改关系接口
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param array $syncParams 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function saveSyncById(Request $request, Controller $controller, $model_name, $syncParams, &$id, $notLog = 0){
        $company_id = $controller->company_id;
        return CommonBusiness::saveSyncByIdApi($model_name, $id, $syncParams, $company_id, $notLog);
    }

    /**
     * 通过id移除关系接口
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param array $syncParams 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function detachById(Request $request, Controller $controller, $modelName, $id, $detachParams, $notLog = 0){
        $company_id = $controller->company_id;
        return CommonBusiness::detachByIdApi($modelName, $id, $detachParams, $company_id, $notLog);
    }

    /**
     * 获得历史员工记录id, 可缓存
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  int 历史员工记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function getStaffHistoryId(Request $request, Controller $controller){
        $company_id = $controller->company_id;
        $operate_staff_id = $controller->operate_staff_id;
        // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
        if( ($controller->cache_sel & 1) == 1){
            $cachePre = 'operate_staff_history_id' ;// __FUNCTION__;// 缓存前缀
            $cacheKey = '';// 缓存键[没算前缀]
            $paramKeyValArr = [$company_id, $operate_staff_id];//[$company_id, $operate_no];// 关键参数  $request->input()
            $cacheResult =$controller->getCacheData($cachePre,$cacheKey, $paramKeyValArr,2, 1);
            if($cacheResult !== false) {
                return $cacheResult;
            }
        }


        // 获得操作员工历史记录id
        $operate_staff_history_id = self::getHistoryId($request, $controller, 'CompanyStaff', $operate_staff_id
            , 'CompanyStaffHistory', 'company_staff_history', ['company_id' => $company_id,'staff_id' => $operate_staff_id], []
            , $company_id, 0);

        // 缓存数据 10分钟
        if( ($controller->cache_sel & 2) == 2) {
            $controller->setCacheData($cachePre, $cacheKey, $operate_staff_history_id, 10*60, 2);
        }
        return $operate_staff_history_id;

    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 需要操作的数组 [一维或二维数组]
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function addOprate(Request $request, Controller $controller, &$saveData){
        $company_id = $controller->company_id;
        $operate_staff_id = $controller->operate_staff_id;
        $operate_staff_history_id = self::getStaffHistoryId($request, $controller);
        // 加入操作人员信息
        $oprateArr = [
            'operate_staff_id' => $controller->operate_staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,// $controller->operate_staff_history_id,
        ];
        $isMultiArr = false; // true:二维;false:一维
        foreach($saveData as $k => $v){
            if(is_array($v)){
                $isMultiArr = true;
            }
            break;
        }
        if($isMultiArr){ //二维

            foreach($saveData as $k => $v){
                $v = array_merge($v, $oprateArr);
                $saveData[$k] = $v;
            }
        }else{// 一维
            $saveData = array_merge($saveData, $oprateArr);
        }
        return $saveData;
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $mainObj 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param obj $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)  格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  int 历史记录表id
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersion(Request $request, Controller $controller, $mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $forceIncVersion= 1, $companyId = null , $notLog = 0){
        return CommonBusiness::compareHistoryOrUpdateVersionApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields, $forceIncVersion, $companyId, $notLog);
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
    public static function getHistoryId(Request $request, Controller $controller, $mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $companyId = null , $notLog = 0){
        return CommonBusiness::getHistoryIdApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields, $companyId, $notLog);
    }
}