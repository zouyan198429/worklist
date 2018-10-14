<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyWork;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;

class WorkController extends AdminController
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4';// 需要播放提示声音的状态，多个逗号,分隔
        return view('admin.work.index', $reDataArr);
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
            $resultDatas =CompanyWork::getShowInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('admin.work.info', $reDataArr);
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
        $countArr = CompanyWork::statusCount($request, $this);
        return ajaxDataArr(1, $countArr, '');
    }

    /**
     * ajax获得工单统计
     *
     * @param Request $request
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_work_count(Request $request){
        $this->InitParams($request);

        $count_type = Common::get($request, 'count_type');// 统计类型 1 按天统计[当月天的] ; 2 按月统计[当年的]; 3 按年统计
        if(!in_array($count_type, [1,2,3])){
            return ajaxDataArr(0, null, '请选择统计类型！');
        }

        $begin_date = Common::get($request, 'begin_date');// 开始日期
        $end_date = Common::get($request, 'end_date');// 结束日期

        if(empty($end_date)) $end_date = date("Y-m-d");
        $today_date = date("Y-m-d");
        $operate_no = 0;
        $title = "";
        switch ($count_type)
        {
            case 1:// 1 按天统计[当月天的] ;
                if(empty($begin_date)) $begin_date = date("Y-m-01");
                $operate_no = 4;
                $title = "按天统计" . $begin_date . '--' . $end_date;
                break;
            case 2:// 2 按月统计[当年的]
                if(empty($begin_date)) $begin_date = date("Y-01-01");
                $operate_no = 8;
                $title = "按月统计" . $begin_date . '--' . $end_date;
                break;
            case 3:// ; 3 按年统计
                $operate_no = 16;
                $title = "按年统计" . $begin_date . '--' . $end_date;
                break;
            default:
        }
        if($today_date == $end_date){
            $title .= '(今天)';
        }

        $nowTime = time();

        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_date, $end_date, 4 + 32 + 256);
        // ajaxDataArr(0, null, '结束日期不能小于开始日期！');

        // 按天统计[当前天的] ;按月统计[当年的]; 按年统计  ,外加按时间段[暂不处理]
        $params = [
            'operate_no' => $operate_no,// 操作编号
            'send_department_id' => '0',// 派到部门id
            'send_group_id' => '0',// 派到小组id
            'department_id' => '0',// 部门id
            'group_id' => '0',// 小组id
            'staff_id' => '0',// 接收员工id
            'operate_staff_id' => '0',// 添加员工id
            'begin_date' => $begin_date,// 开始日期
            'end_date' => $end_date,// 结束日期
        ];
        $countArr = CompanyWork::workCount($request, $this, $params);
        $reArr = [
            'countList' => [],// 列表数据
            'title' => $title, // 名称
            'dataAxis' => [],// x坐标名称 -一维数组
            'dataY' => [],// y坐标数据 -一维数组
            'yMax' => 0 ,// y坐标数据最大显示[最大数据+三分之一]
        ];
        $countList = [];
        switch ($count_type)
        {
            case 1:// 1 按天统计[当月天的] ;
                $countData = $countArr['callCountDay'] ?? [];
                $countList = Tool::formatTwoArrKeys($countData, Tool::arrEqualKeyVal(['count_date', 'amount']), false);
                break;
            case 2:// 2 按月统计[当年的]
                $countData = $countArr['callCountMonth'] ?? [];
                foreach($countData as $v){
                    array_push($countList,['count_date' => $v['count_year'] . '-' . $v['count_month'], 'amount' => $v['amount'] ]);
                }
                break;
            case 3:// ; 3 按年统计
                $countData = $countArr['callCountYear'] ?? [];
                $countList = Tool::formatTwoArrKeys($countData, ['count_date' => 'count_year', 'amount' => 'amount'], false);
                break;
            default:
        }
        $reArr['dataAxis'] = array_column($countList,'count_date');
        $reArr['dataY'] = array_column($countList,'amount');
        $yMax =  1;
        if(!empty($reArr['dataY'])) $yMax = max($reArr['dataY']);
        if(!is_numeric($yMax) || $yMax <= 0) $yMax = 1;
        $reArr['yMax'] = $yMax + ceil(1/5 * $yMax);
        $reArr['data_list'] = $countList;
        return ajaxDataArr(1, $reArr, '');
    }

    /**
     * ajax获得工单统计
     *
     * @param Request $request
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_repair_count(Request $request){
        $this->InitParams($request);

        $count_type = Common::get($request, 'count_type');// 统计类型 1 按天统计[当月天的] ; 2 按月统计[当年的]; 3 按年统计
        if(!in_array($count_type, [1,2,3])){
            return ajaxDataArr(0, null, '请选择统计类型！');
        }

        $begin_date = Common::get($request, 'begin_date');// 开始日期
        $end_date = Common::get($request, 'end_date');// 结束日期

        if(empty($end_date)) $end_date = date("Y-m-d");
        $today_date = date("Y-m-d");
        $operate_no = 0;
        $title = "";
        switch ($count_type)
        {
            case 1:// 1 按天统计[当月天的] ;
                if(empty($begin_date)) $begin_date = date("Y-m-01");
                $operate_no = 128;
                $title = "按天统计" . $begin_date . '--' . $end_date;
                break;
            case 2:// 2 按月统计[当年的]
                if(empty($begin_date)) $begin_date = date("Y-01-01");
                $operate_no = 256;
                $title = "按月统计" . $begin_date . '--' . $end_date;
                break;
            case 3:// ; 3 按年统计
                $operate_no = 512;
                $title = "按年统计" . $begin_date . '--' . $end_date;
                break;
            default:
        }
        if($today_date == $end_date){
            $title .= '(今天)';
        }

        $nowTime = time();

        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_date, $end_date, 4 + 32 + 256);
        // ajaxDataArr(0, null, '结束日期不能小于开始日期！');

        // 按天统计[当前天的] ;按月统计[当年的]; 按年统计  ,外加按时间段[暂不处理]
        $params = [
            'operate_no' => $operate_no,// 操作编号
            'send_department_id' => '0',// 派到部门id
            'send_group_id' => '0',// 派到小组id
            'department_id' => '0',// 部门id
            'group_id' => '0',// 小组id
            'staff_id' => '0',// 接收员工id
            'operate_staff_id' => '0',// 添加员工id
            'begin_date' => $begin_date,// 开始日期
            'end_date' => $end_date,// 结束日期
        ];
        $countArr = CompanyWork::workCount($request, $this, $params);
        $reArr = [
            'countList' => [],// 列表数据
            'title' => $title, // 名称
            'dataAxis' => [],// x坐标名称 -一维数组
            'dataY' => [],// y坐标数据 -一维数组
            'yMax' => 0 ,// y坐标数据最大显示[最大数据+三分之一]
        ];
        $countList = [];
        switch ($count_type)
        {
            case 1:// 1 按天统计[当月天的] ;
                $countData = $countArr['repairCountDay'] ?? [];
                $countList = Tool::formatTwoArrKeys($countData, Tool::arrEqualKeyVal(['count_date', 'amount']), false);
                break;
            case 2:// 2 按月统计[当年的]
                $countData = $countArr['repairCountMonth'] ?? [];
                foreach($countData as $v){
                    array_push($countList,['count_date' => $v['count_year'] . '-' . $v['count_month'], 'amount' => $v['amount'] ]);
                }
                break;
            case 3:// ; 3 按年统计
                $countData = $countArr['repairCountYear'] ?? [];
                $countList = Tool::formatTwoArrKeys($countData, ['count_date' => 'count_year', 'amount' => 'amount'], false);
                break;
            default:
        }
        $reArr['dataAxis'] = array_column($countList,'count_date');
        $reArr['dataY'] = array_column($countList,'amount');
        $yMax =  1;
        if(!empty($reArr['dataY'])) $yMax = max($reArr['dataY']);

        if(!is_numeric($yMax) || $yMax <= 0) $yMax = 1;
        $reArr['yMax'] = $yMax + ceil(1/5 * $yMax);
        $reArr['data_list'] = $countList;
        return ajaxDataArr(1, $reArr, '');
    }

}
