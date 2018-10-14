<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;

class WorkController extends WorksController
{

    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function list(Request $request)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//        $reDataArr['status'] =  CompanyWork::$status_arr;
//        return view('m.work.list', $reDataArr);
//    }

    /**
     * 结单页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function win(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $company_id = $this->company_id;
        $resultDatas = [
            'id'=>$id,
            'department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyWork::getInfoData($request, $this, $id);
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
                'send_staff_id' => $this->user_id,
                'status' => 2,
            ];
            CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // pr($reDataArr);

        return view('mobile.work.win', $reDataArr);
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
     * 导出
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function export(Request $request){
        $this->InitParams($request);
        CompanyWork::getList($request, $this, 1 + 0);
    }

    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function import_template(Request $request){
        $this->InitParams($request);
        CompanyWork::importTemplate($request, $this);
    }


    /**
     * ajax获得所有数据 根据状态值
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_doing_list(Request $request){
        $this->InitParams($request);
        $status = Common::get($request, 'status');
        if(empty($status)){
            $status = 1;
        }
        return  CompanyWork::getListByStatus($request, $this, 1 + 4, $status);
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


    /**
     * ajax确认
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_sure(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        $saveData = [];
        $resultDatas = CompanyWork::workSure($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * ajax结单
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_win(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        $win_content = Common::get($request, 'win_content');
        $win_content =  replace_enter_char($win_content,1);
        $saveData = [
            'win_content' => $win_content,// 内容
        ];
        $resultDatas = CompanyWork::workWin($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * ajax获得工单状态统计
     *
     * @param Request $request
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_status_count(Request $request){
        $this->InitParams($request);
        $user_id = $this->user_id;
        $countArr = CompanyWork::statusCount($request, $this,$user_id,0);
        return ajaxDataArr(1, $countArr, '');
    }

    /**
     * ajax获得工单处理数量统计
     *
     * @param Request $request
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_work_sum(Request $request){
        $this->InitParams($request);
        $user_id = $this->user_id;
        $countArr = CompanyWork::sumCount($request, $this,$user_id,0);
        return ajaxDataArr(1, $countArr, '');
    }
}
