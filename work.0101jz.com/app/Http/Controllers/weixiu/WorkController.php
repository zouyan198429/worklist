<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyWork;
use App\Business\CompanyWorkDoing;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use App\Services\CommonBusiness;
use Illuminate\Http\Request;

class WorkController extends WorksController
{

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
            $resultDatas =CompanyWork::getShowInfoData($request, $this, $id);
            //$resultDatas = CompanyWork::getInfoData($request, $this, $id);
            $win_content = $resultDatas['win_content'] ?? '';
            $resultDatas['win_content'] = replace_enter_char($win_content,2);
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
                'send_staff_id' => $this->user_id,
                'status' => 2,
            ];
            CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);

        return view('weixiu.work.win', $reDataArr);
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
        return view('weixiu.work.list', $reDataArr);
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
        return view('weixiu.work.info', $reDataArr);
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
                ['send_staff_id', $user_id],
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
        // return  CompanyWork::getListByStatus($request, $this, 1 + 4, $status);
        return  CompanyWorkDoing::getListByStatus($request, $this, 1 + 4, $status);
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

}
