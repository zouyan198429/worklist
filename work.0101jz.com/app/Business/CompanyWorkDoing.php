<?php
// 工单
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyWorkDoing extends CompanyWork
{
    protected static $model_name = 'CompanyWorkDoing';


    /**
     * 通过状态获得列表数据--数据[待处理]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param mixed $relations 关系
     * @param int $status 状态0新工单2待反馈工单;4待回访工单;8已完成工单 , 如果要查多个用逗号分隔 ,
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function getListByStatus(Request $request, Controller $controller, $oprateBit = 2 + 4, $status = 0, $relations = '', $notLog = 0){
//        $company_id = $controller->company_id;
//
//        // 获得数据
//        $queryParams = [
//            'where' => [
//                ['company_id', $company_id],
//                ['send_staff_id', $controller->user_id],
//                // ['status', $status],
//            ],
////            'whereIn' => [
////                'status'=> [0,1,2,4,8],
////            ],
////            'select' => [
////                'id','company_id','type_name','sort_num'
////                //,'operate_staff_id','operate_staff_history_id'
////                ,'created_at'
////            ],
////            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
//            'orderBy' => ['expiry_time'=>'desc','id'=>'desc'],
//        ];
//        $statusArr = explode(',', $status);
//        if(in_array(8, $statusArr)){// 有已完成的状态
//             return CompanyWork::getListByStatus($request, $controller, $oprateBit, $status, $relations, $notLog);
//        }
//        if(count($statusArr) > 1){
//            $queryParams['whereIn'] = [
//                'status'=> $statusArr,
//            ];
//        }else{
//            array_push($queryParams['where'],['status', $status]);
//        }
////        if(in_array($status, [1,2])){
////            $queryParams['orderBy'] = ['book_time'=>'desc','id'=>'desc'];
////        }else{
////            $queryParams['orderBy'] = ['id'=>'desc'];
////        }
//        // 查询条件参数
//        // $relations = ['CompanyInfo'];// 关系
//        // $relations = '';//['CompanyInfo'];// 关系
//        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);
//
//        // 格式化数据
//        $data_list = $result['data_list'] ?? [];
//        foreach($data_list as $k => $v){
//            // 列表id 换成真正表id work_id字段
//            if(! isset($data_list[$k]['work_id'])) $data_list[$k]['id'] = $v['work_id'];
////            // 公司名称
////            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
////            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        // Tool::formatTwoArrKeys($data_list,['id', 'work_num', 'caller_type_name', 'customer_id', 'customer_name'], false);
//        $result['data_list'] = $data_list;
//        // return ajaxDataArr(1, $result, '');
//        // 格式化数据
//        // return $result;
//        return ajaxDataArr(1, $result, '');
//    }

}
