<?php

namespace App\Http\Controllers\huawu;

use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkController extends WorksController
{
    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('huawu.work.index', $reDataArr);
    }

    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('huawu.work.list', $reDataArr);
    }

    /**
     * history
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function history(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('huawu.work.history', $reDataArr);
    }

    /**
     * hot
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function hot(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('huawu.work.hot', $reDataArr);
    }

    /**
     * re_list
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function re_list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('huawu.work.re_list', $reDataArr);
    }


    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);

        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            'department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyWork::getInfoData($request, $this, $id, '');
            $content = $resultDatas['content'] ?? '';
            $resultDatas['content'] = replace_enter_char($content,2);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('huawu.work.add', $reDataArr);
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request,$id = 0)
    {
        $this->InitParams($request);

        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            'department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $relations = ['workHistoryStaffCreate', 'workHistoryStaffSend'];
            $resultDatas = CompanyWork::getInfoData($request, $this, $id, $relations);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('huawu.work.info', $reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $caller_type_id = Common::getInt($request, 'caller_type_id');
        $call_number = Common::get($request, 'call_number');
        $work_type_id = Common::getInt($request, 'work_type_id');
        $business_id = Common::getInt($request, 'business_id');
        $content = Common::get($request, 'content');
        $content =  replace_enter_char($content,1);
        $tag_id = Common::getInt($request, 'tag_id');
        $time_id = Common::getInt($request, 'time_id');
        $book_time = Common::get($request, 'book_time');
        //判断预约处理时间
        $book_time_unix = judgeDate($book_time);
        if($book_time_unix === false){
            ajaxDataArr(0, null, '预约处理时间不是有效日期');
        }
        $customer_name = Common::get($request, 'customer_name');
        $sex = Common::getInt($request, 'sex');
        $type_id = Common::getInt($request, 'type_id');
        $city_id = Common::getInt($request, 'city_id');
        $area_id = Common::getInt($request, 'area_id');
        $address = Common::get($request, 'address');
        $send_department_id = Common::getInt($request, 'send_department_id');
        $send_group_id = Common::getInt($request, 'send_group_id');
        $send_staff_id = Common::getInt($request, 'send_staff_id');

        $saveData = [
            'caller_type_id' => $caller_type_id, // 来电类型
            'call_number' => $call_number,// 来电号码
            'work_type_id' => $work_type_id, //业务类型
            'business_id' => $business_id,// 业务
            'content' => $content,// 内容
            'tag_id' => $tag_id,// 标签
            'time_id' => $time_id,// 工单时长
            'book_time' => $book_time,// 预约处理时间
            'customer_name' => $customer_name,// 客户姓名
            'sex' => $sex,// 性别
            'type_id' => $type_id,// 客户类别
            'city_id' => $city_id,// 区县
            'area_id' => $area_id,// 街道
            'address' => $address,// 详细地址
            'send_department_id' => $send_department_id,
            'send_group_id' => $send_group_id,
            'send_staff_id' => $send_staff_id,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyWork::saveById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyWork::getList($request, $this, 2 + 4);
    }

    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_del(Request $request)
//    {
//        $this->InitParams($request);
//        return CompanyWork::delAjax($request, $this);
//    }

}
