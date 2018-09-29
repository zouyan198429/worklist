<?php

namespace App\Http\Controllers\huawu;

use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use App\Services\Tool;
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
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
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
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
            'book_time' => Carbon::now()->toDateTimeString(),
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
            $resultDatas =CompanyWork::getShowInfoData($request, $this, $id);
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
        $contact_number = Common::get($request, 'contact_number');
        $work_type_id = Common::getInt($request, 'work_type_id');
        $business_id = Common::getInt($request, 'business_id');
        $content = Common::get($request, 'content');
        $content =  replace_enter_char($content,1);
        $tag_id = Common::getInt($request, 'tag_id');
        $time_id = Common::getInt($request, 'time_id');
//        $book_time = Common::get($request, 'book_time');
//        //判断预约处理时间
//        $book_time_unix = judgeDate($book_time);
//        if($book_time_unix === false){
//            ajaxDataArr(0, null, '预约处理时间不是有效日期');
//        }
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
            'contact_number' => $contact_number,// 联系电话
            'work_type_id' => $work_type_id, //业务类型
            'business_id' => $business_id,// 业务
            'content' => $content,// 内容
            'tag_id' => $tag_id,// 标签
            'time_id' => $time_id,// 工单时长
            //'book_time' => $book_time,// 预约处理时间
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
        $company_id = $this->company_id;
        $user_id = $this->user_id;
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['operate_staff_id', $user_id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        return  CompanyWork::getList($request, $this, 2 + 4,$queryParams);
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
     * 反馈问题的回复
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function reply(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            //  'call_number' => $this->user_info['mobile'] ?? '',
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas =CompanyWork::getShowInfoData($request, $this, $id);
            //$resultDatas = CompanyWork::getInfoData($request, $this, $id, '');
            $reply_content = $resultDatas['reply_content'] ?? '';
            $resultDatas['reply_content'] = replace_enter_char($reply_content,2);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('huawu.work.reply',$reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function reply_ajax_save(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        $reply_content = Common::get($request, 'reply_content');
        $reply_content =  replace_enter_char($reply_content,1);
        $saveData = [
            'reply_content' => $reply_content,// 内容
        ];
        $resultDatas = CompanyWork::workReply($request,  $this, $saveData , $id);
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
        $countArr = CompanyWork::statusCount($request, $this,0, $user_id);
        return ajaxDataArr(1, $countArr, '');
    }

}
