<?php

namespace App\Http\Controllers;


use App\Models\CompanyArea;
use App\Models\CompanyCustomerType;
use App\Models\CompanyDepartment;
use App\Models\CompanyServiceTags;
use App\Models\CompanyServiceTime;
use App\Models\CompanyWorkCallerType;
use App\Models\CompanyWorkType;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;

class CompanyWorkController extends CompController
{
    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function addInit(Request $request)
    {

        $this->InitParams($request);
        $company_id = $this->company_id;
        // operate_no 操作编号
        $operate_no = Common::getInt($request, 'operate_no');

        // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
        if( ($this->cache_sel & 1) == 1){
            $cachePre = __FUNCTION__;// 缓存前缀
            $cacheKey = '';// 缓存键[没算前缀]
            $paramKeyValArr = $request->input();//[$company_id, $operate_no];// 关键参数  $request->input()
            $cacheResult =$this->getCacheData($cachePre,$cacheKey, $paramKeyValArr );
            if($cacheResult !== false) return $cacheResult;
        }

        $listData = [];
        //工单分类第一级 1
        if(($operate_no & 1) == 1 ){
            $workFirstList = CompanyWorkType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['type_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['workFirstList'] = Tool::formatArrKeyVal($workFirstList, 'id', 'type_name');
        }

        //工单来电类型 2
        if(($operate_no & 2) == 2 ) {
            $workCallTypeList = CompanyWorkCallerType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['workCallTypeList'] = Tool::formatArrKeyVal($workCallTypeList, 'id', 'type_name');
        }

        //业务标签 4
        if(($operate_no & 4) == 4 ) {
            $serviceTagList = CompanyServiceTags::select(['id', 'tag_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['serviceTagList'] = Tool::formatArrKeyVal($serviceTagList, 'id', 'tag_name');
        }
        // 业务时间 8
        if(($operate_no & 8) == 8 ) {
            $serviceTimeList = CompanyServiceTime::select(['id', 'time_name', 'second_num'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['serviceTimeList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'time_name');
            $listData['serviceTimeMinList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'second_num');
        }

        // 客户类型 16
        if(($operate_no & 16) == 16 ) {
            $customerTypeList = CompanyCustomerType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['customerTypeList'] = Tool::formatArrKeyVal($customerTypeList, 'id', 'type_name');
        }

        // 客户地区 32
        if(($operate_no & 32) == 32 ) {
            $areaCityList = CompanyArea::select(['id', 'area_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['area_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['areaCityList'] = Tool::formatArrKeyVal($areaCityList, 'id', 'area_name');
        }

        // 部门信息 64
        if(($operate_no & 64) == 64 ) {
            $departmentFirstList = CompanyDepartment::select(['id', 'department_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['department_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['departmentFirstList'] = Tool::formatArrKeyVal($departmentFirstList, 'id', 'department_name');
        }
//        $listData = [
//            'workFirstList' => $workFirstList,// 工单分类第一级
//            'workCallTypeList' => $page,// 工单来电类型
//            'serviceTagList' => $total,//业务标签
//            'serviceTimeList' => $aaa,// 业务时间
//            'customerTypeList' => $requestData,// 客户类型
//            'areaCityList' => $requestData,// 客户地区
//            'departmentFirstList' => $requestData,// 部门信息
//        ];
        $resultData = okArray($listData);
        // 缓存数据
        if( ($this->cache_sel & 2) == 2) {
            $this->setCacheData($cachePre, $cacheKey, $resultData, 60, 1);
        }
        return $resultData;
    }
}
