<?php
// 反馈类型[二级分类]
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyProblemType extends BaseBusiness
{
    protected static $model_name = 'CompanyProblemType';

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据[一维的键=>值数组]
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListKeyVal(Request $request, Controller $controller, $id, $oprateBit = 2 + 4, $notLog = 0){
        $parentData = self::getChildList($request, $controller, $id, $oprateBit, $notLog);
        $department_list = $parentData['result']['data_list'] ?? [];
        return Tool::formatArrKeyVal($department_list, 'id', 'type_name');
    }
    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildList(Request $request, Controller $controller, $id, $oprateBit = 2 + 4, $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['type_parent_id', $id],
            ],
            'select' => [
                'id', 'company_id', 'type_name', 'sort_num', 'type_parent_id'
                //,'operate_staff_id','operate_staff_history_id'
                ,'created_at'
            ],
            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
        ];// 查询条件参数
        // $relations = ['CompanyInfo'];// 关系
        $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
//        $data_list = $result['data_list'] ?? [];
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['data_list'] = $data_list;
//        $resultDatas = $result['data_list'] ?? [];
//        $format_data_list = [];
//        foreach($resultDatas as $v){
//            $parent_id = $v['type_parent_id'];
//            if($parent_id > 0 ){
//                $v['business_name'] =  $v['type_name'];
//                $v['type_name'] = "";
//            }else{
//                $v['business_name']  = "";
//            }
//            $format_data_list[$parent_id][] = $v;
//        }
//
//        $first_list = $format_data_list[0] ?? [];
//
//        $data_list = [];
//        foreach($first_list as $v){
//            $data_list[] = $v;
//            $id = $v['id'];
//            $tem_arr = $format_data_list[$id] ?? [];
//            if(empty($tem_arr)){
//                continue;
//            }
//            $data_list = array_merge($data_list, $tem_arr);
//        }
//        $result['data_list'] = $data_list;
        return ajaxDataArr(1, $result, '');
    }
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
            'select' => [
                'id', 'company_id', 'type_name', 'sort_num', 'type_parent_id'
                //,'operate_staff_id','operate_staff_history_id'
                ,'created_at'
            ],
            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
//        $data_list = $result['data_list'] ?? [];
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['data_list'] = $data_list;
        $resultDatas = $result['data_list'] ?? [];
        $format_data_list = [];
        foreach($resultDatas as $v){
            $parent_id = $v['type_parent_id'];
            if($parent_id > 0 ){
                $v['business_name'] =  $v['type_name'];
                $v['type_name'] = "";
            }else{
                $v['business_name']  = "";
            }
            $format_data_list[$parent_id][] = $v;
        }

        $first_list = $format_data_list[0] ?? [];

        $data_list = [];
        foreach($first_list as $v){
            $data_list[] = $v;
            $id = $v['id'];
            $tem_arr = $format_data_list[$id] ?? [];
            if(empty($tem_arr)){
                continue;
            }
            $data_list = array_merge($data_list, $tem_arr);
        }
        $result['data_list'] = $data_list;

        return ajaxDataArr(1, $result, '');
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
        $id = Common::getInt($request, 'id');
        $company_id = $controller->company_id;

        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        $relations = '';
        CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations);

        // 删除当前记录
        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
                ['company_id', $company_id]
            ]
        ];

        $resultDatas = CommonBusiness::ajaxDelApi(self::$model_name, $company_id , $queryParams);

        // 删除子分类
        $queryParams =[// 查询条件参数
            'where' => [
                ['type_parent_id', $id],
                ['company_id', $company_id]
            ]
        ];
        CommonBusiness::ajaxDelApi(self::$model_name, $company_id , $queryParams);

        return ajaxDataArr(1, $resultDatas, '');;

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
        // 加入操作人员信息
        self::addOprate($request, $controller, $saveData);
        // 新加或修改
        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
    }
}
