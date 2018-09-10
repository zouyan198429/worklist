<?php

namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
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
            $total = count($resultDatas);
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
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjaxBase(Request $request, Controller $controller, $model_name, $notLog = 0){

        $id = Common::getInt($request, 'id');
        $company_id = $controller->company_id;

        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        $relations = '';
        CommonBusiness::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);

        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
                ['company_id', $company_id]
            ]
        ];
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

}