<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanyNotice;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class NoticeController extends AdminController
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
        return view('manage.notice.index', $reDataArr);
    }


    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request, $id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $resultDatas = CompanyNotice::getInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['operate'] = $operate;
        return view('manage.notice.add', $reDataArr);
    }


    /**
     * 详情
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request, $id = 0)
    {
        $this->InitParams($request);

        $reDataArr = $this->reDataArr;

        // 详情信息
        $resultDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas =CompanyNotice::getInfoData($request, $this, $id, ['oprateStaffHistory']);
            // 修改点击点
            $id = $resultDatas['id'] ??  0;
            $volume = $resultDatas['volume'] ??  0;
            $saveData = [
                'volume' => $volume + 1,
            ];
            CompanyNotice::replaceById($request, $this, $saveData, $id, false);
            $resultDatas['volume'] = $volume + 1;
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 上一条
        $preList = CompanyNotice::getNearList($request, $this, $id, 1, 1, 0, [], '');
        $reDataArr['preList'] = $preList;
        // 下一条
        $nextList = CompanyNotice::getNearList($request, $this, $id, 2, 1, 0, [], '');
        $reDataArr['nextList'] = $nextList;
        return view('manage.notice.info', $reDataArr);
    }

    /**
     * ajax获得客户列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyNotice::getList($request, $this, 2 + 4, [], ['oprateStaffHistory']);
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
        CompanyNotice::getList($request, $this, 1 + 0);
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
        CompanyNotice::importTemplate($request, $this);
    }


    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyNotice::delAjax($request, $this);
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
        $title = Common::get($request, 'title');
        $resource = Common::get($request, 'resource');
        $content = Common::get($request, 'content');
        $content = stripslashes($content);

        $saveData = [
            'title' => $title,
            'resource' => $resource,
            'content' => $content,
        ];

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyNotice::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
