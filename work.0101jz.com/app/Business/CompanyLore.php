<?php
// 知识
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
class CompanyLore extends BaseBusiness
{
    protected static $model_name = 'CompanyLore';

    // 推荐级别1-5星
    public static $level_num_arr = [
        '1' => '★',
        '2' => '★★',
        '3' => '★★★',
        '4' => '★★★★',
        '5' => '★★★★★',
    ];

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        // $params = self::formatListParams($request, $controller, $queryParams);

        $type_id = Common::getInt($request, 'type_id');
        if($type_id > 0){
            array_push($queryParams['where'],['type_id', $type_id]);
        }

        $title = Common::get($request, 'title');
        if(!empty($title)){
            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
        }

        $position_ids = Common::get($request, 'position_ids');
        if(!empty($position_ids)){
            array_push($queryParams['where'],['position_ids', 'like' , '%,' . $position_ids . ',%']);
        }

        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
        if (!empty($ids)) {
            if (strpos($ids, ',') === false) { // 单条
                array_push($queryParams['where'],['id', $ids]);
            }else{
                $queryParams['whereIn']['id'] = explode(',',$ids);
            }
        }
        $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
        if($isExport == 1) $oprateBit = 1;
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 添加人
            $data_list[$k]['real_name'] = $v['oprate_staff_history']['real_name'] ?? '';
            if(isset($data_list[$k]['oprate_staff_history'])) unset($data_list[$k]['oprate_staff_history']);
            // 去掉内容
            if(isset($data_list[$k]['content'])) unset($data_list[$k]['content']);
            // 适合的岗位信息
            if(isset($data_list[$k]['lore_positions'])){
                $lore_positions = $data_list[$k]['lore_positions'];
                $data_list[$k]['positionIds'] = array_column($lore_positions,'id');
                $data_list[$k]['position_id_kv'] = Tool::formatArrKeyVal($lore_positions, 'id', 'position_name');
                $data_list[$k]['position_names'] = implode('、',array_column($lore_positions, 'position_name'));
                unset($data_list[$k]['lore_positions']);
            }
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
//            $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//            ImportExport::export('','excel文件名称',$data_list,1, $headArr, 0, ['sheet_title' => 'sheet名称']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 格式化列表查询条件-暂不用
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $queryParams 条件数组/json字符
     * @return  array 参数数组 一维数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function formatListParams(Request $request, Controller $controller, &$queryParams = []){
//        $params = [];
//        $title = Common::get($request, 'title');
//        if(!empty($title)){
//            $params['title'] = $title;
//            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
//        }
//
//        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
//        if (!empty($ids)) {
//            $params['ids'] = $ids;
//            if (strpos($ids, ',') === false) { // 单条
//                array_push($queryParams['where'],['id', $ids]);
//            }else{
//                $queryParams['whereIn']['id'] = explode(',',$ids);
//                $params['idArr'] = explode(',',$ids);
//            }
//        }
//        return $params;
//    }

    /**
     * 获得当前记录前/后**条数据--二维数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $nearType 类型 1:前**条[默认]；2后**条
     * @param int $limit 数量 **条
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据 - 二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearList(Request $request, Controller $controller, $id = 0, $nearType = 1, $limit = 1, $queryParams = [], $relations = '', $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 前**条[默认]
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
//                ['id', '>', $id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'asc'],
            'limit' => $limit,
            'offset' => 0,
            // 'count'=>'0'
        ];
        if($nearType == 1){// 前**条
            $defaultQueryParams['orderBy'] = ['id'=>'asc'];
            array_push($defaultQueryParams['where'],['id', '>', $id]);
        }else{// 后*条
            array_push($defaultQueryParams['where'],['id', '<', $id]);
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, $notLog);
        // 格式化数据
        $data_list = $result['result']['data_list'] ?? [];
        if($nearType == 1) $data_list = array_reverse($data_list); // 相反;
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['result']['data_list'] = $data_list;
        return $data_list;
    }

    /**
     * 导入模版
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function importTemplate(Request $request, Controller $controller)
    {
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }
    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller)
    {
        $id = Common::get($request, 'id');
        Tool::dataValid([["input"=>$id,"require"=>"true","validator"=>"","message"=>'参数id值不能为空']]);

        $company_id = $controller->company_id;
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        $relations = '';
        CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, 0);

        $selIds = explode(',',$id);
        foreach ($selIds as $temId){
            // 删除关系
            // 无删除移除关系表--注意要先删除关系
            $detachParams =[
                'lorePositions' => [],//适合的职位
            ];
            $detachDatas = self::detachById($request, $controller, self::$model_name, $temId, $detachParams, 0);
        }
        $result = self::delAjaxBase($request, $controller, self::$model_name, 0 + 2);

        return $result;

    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $relations = ''){
        $company_id = $controller->company_id;
        // $relations = '';
        $resultDatas = CommonBusiness::getinfoApi(self::$model_name, $relations, $company_id , $id);
        // $resultDatas = self::getInfoDataBase($request, $controller, self::$model_name, $id, $relations);
        // 添加人
        $resultDatas['real_name'] = $resultDatas['oprate_staff_history']['real_name'] ?? '';
        if(isset($resultDatas['oprate_staff_history'])) unset($resultDatas['oprate_staff_history']);
        // 适合的岗位信息
        if(isset($resultDatas['lore_positions'])){
            $lore_positions = $resultDatas['lore_positions'];
            $resultDatas['positionIds'] = array_column($lore_positions,'id');
            $resultDatas['position_id_kv'] = Tool::formatArrKeyVal($lore_positions, 'id', 'position_name');
            $resultDatas['position_names'] = implode('、',array_column($lore_positions, 'position_name'));
            unset($resultDatas['lore_positions']);
        }
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
        return $resultDatas;
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);


        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
        }
        // 是否有知识适合职位
        $hasPosition = false;
        $positionIds = [];
        if(isset($saveData['positionIds'])){
            $hasPosition = true;
            $positionIds = $saveData['positionIds'];
            unset($saveData['positionIds']);
        }
        // 加入操作人员信息
        self::addOprate($request, $controller, $saveData);
        // 新加或修改
        $result = self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);

        // 同步修改关系
        if($hasPosition){
            // 加入company_id字段
            $syncPositionArr = [];
            foreach($positionIds as $positionId){
                $temArr =  [
                    'company_id' => $company_id,
                ];
                // 加入操作人员信息
                self::addOprate($request, $controller, $temArr);
                $syncPositionArr[$positionId] = $temArr;
            }
            $syncParams =[
                'lorePositions' => $syncPositionArr,//适合职位
            ];
            self::saveSyncById($request, $controller, self::$model_name, $syncParams,$id,0);
        }
        return $result;
    }
}
