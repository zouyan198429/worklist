<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
use App\Business\CompanyPosition;
use App\Business\CompanyStaff;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use App\Services\Excel\ImportExport;
use Illuminate\Http\Request;

class StaffController extends AdminController
{
    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('admin.staff.index', $reDataArr);
    }


    /**
     * 添加
     *
     * @param Request $request
     * @param int $id
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
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $resultDatas = CompanyStaff::getInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['operate'] = $operate;
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);
        // 获得第一级职位分类一维数组[$k=>$v]
        $reDataArr['position_kv'] = CompanyPosition::getListKeyVal($request, $this, 1 + 0);

        return view('admin.staff.add', $reDataArr);
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
        $work_num = Common::get($request, 'work_num');
        $department_id = Common::getInt($request, 'department_id');
        $group_id = Common::getInt($request, 'group_id');
        $position_id = Common::getInt($request, 'position_id');
        $real_name = Common::get($request, 'real_name');
        $sex = Common::getInt($request, 'sex');
        $mobile = Common::get($request, 'mobile');
//        $tel = Common::get($request, 'tel');
//        $qq_number = Common::get($request, 'qq_number');
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');

        $saveData = [
            'work_num' => $work_num,
            'department_id' => $department_id,
            'group_id' => $group_id,
            'position_id' => $position_id,
            'real_name' => $real_name,
            'sex' => $sex,
            'mobile' => $mobile,
//            'tel' => $tel,
//            'qq_number' => $qq_number,
            'admin_username' => $admin_username,
        ];
        if($admin_password != '' || $sure_password != ''){
            if ($admin_password != $sure_password){
                return ajaxDataArr(0, null, '密码和确定密码不一致！');
            }
            $saveData['admin_password'] = $admin_password;
        }

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyStaff::replaceById($request, $this, $saveData, $id);
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
        return  CompanyStaff::getList($request, $this, 2 + 4);
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
        CompanyStaff::getList($request, $this, 1 + 0);
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
        CompanyStaff::importTemplate($request, $this);
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
        return CompanyStaff::delAjax($request, $this);
    }

    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $department_id = Common::getInt($request, 'department_id');
        $group_id = Common::getInt($request, 'group_id');
        // 获得所属部门小组下的员工一维数组[$k=>$v]
        $staffChildKV = CompanyStaff::getChildListKeyValByDepartment($request, $this, $department_id, $group_id,1 + 0);
        return  ajaxDataArr(1, $staffChildKV, '');;
    }


    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_areachild(Request $request){
        $this->InitParams($request);
        $city_id = Common::getInt($request, 'city_id');
        $area_id = Common::getInt($request, 'area_id');
        // 获得所属区县街道下的员工一维数组[$k=>$v]
        $staffChildKV = CompanyStaff::getChildListKeyValByArea($request, $this, $city_id, $area_id,1 + 0);
        return  ajaxDataArr(1, $staffChildKV, '');;
    }

    // 导入员工信息
    public function ajax_import_staff(Request $request){
        $this->InitParams($request);
        $fileName = 'staffs.xlsx';
        $dataStartRow = 1;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
        $headRowNum = 1;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
        $headArr = [
            '县区' => 'department',
            '归属营业厅或片区' => 'group',
            '渠道名称' => 'channel',
            '姓名' => 'real_name',
            '工号' => 'work_num',
            '职务' => 'position',
            '手机号' => 'mobile',
            '性别' => 'sex',
        ];
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        $dataArr = ImportExport::import($fileName, $dataStartRow, $headRowNum, $headArr);
        $resultDatas = CompanyStaff::staffImport($request, $this, $dataArr);
        return ajaxDataArr(1, $resultDatas, '');
    }
}
