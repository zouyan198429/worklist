<?php

namespace App\Http\Controllers;


use App\Business\CompanyChannelBusiness;
use App\Business\CompanyDepartmentBusiness;
use App\Business\CompanyPositionBusiness;
use App\Business\CompanyStaffBusiness;
use App\Business\CompanyStaffChannelBusiness;
use App\Models\SiteAdmin;
use App\Services\Common;
use Illuminate\Http\Request;

class CompanyStaffController extends CompController
{
    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    /**
     * 批量导入
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function bathImport(Request $request)
    {
        $this->InitParams($request);
        ini_set('memory_limit','1024M');    // 临时设置最大内存占用为1G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $company_id = $this->company_id;
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $admin_type = Common::getInt($request, 'admin_type');// 类型
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data, 1, '参数[save_data]格式有误!');
        foreach($save_data as $k => $info) {
            $department = $info['department'] ?? '';// 部门名称
            $group = $info['group'] ?? '';// 小组名称
            $channel = $info['channel'] ?? ''; // 渠道名称
            $real_name = $info['real_name'] ?? '';// 用户名
            if(empty($real_name)){
                $real_name = $channel;
                $save_data[$k]['real_name'] = $channel;
            }
            $work_num = $info['work_num'] ?? '';// 工号
            $position = $info['position'] ?? '';// 职位
            $mobile = $info['mobile'] ?? '';// 手机号
            $sex = $info['sex'] ?? '';// 性别
            if (empty($mobile) || empty($work_num)) {
                throws('手机号或工号不能为空');
            }
//            // 判断工号是否已经存在
//            if(CompanyStaffBusiness::judgeExistWorkNum($company_id, $work_num)){
//                throws('工号[' . $work_num . ']已存在');
//            }
//            // 判断手机号是否已经存在
//            if(CompanyStaffBusiness::judgeExistMobile($company_id, $mobile)){
//                throws('手机号[' . $mobile . ']已存在');
//            }
            // 判断工号手机号是否已经存在
//            if(CompanyStaffBusiness::judgeExistWorkNumMobile($company_id, $work_num, $mobile)){
//                throws('工号 + 手机号[' . $mobile . ']已存在');
//            }
        }
        $ExistWorkNumMobile = [];
        $positionArr = [];
        $departmentArr = [];
        $groupArr = [];
        $channelArr = [];
        foreach($save_data as $k => $info){
            $department = $info['department'] ?? '';// 部门名称
            $group = $info['group'] ?? '';// 小组名称
            $channel = $info['channel'] ?? ''; // 渠道名称
            $real_name = $info['real_name'] ?? '';// 用户名
            if(empty($real_name)){
                $real_name = $channel;
                $save_data[$k]['real_name'] = $channel;
            }
            $work_num = $info['work_num'] ?? '';// 工号
            $position = $info['position'] ?? '';// 职位
            $mobile = $info['mobile'] ?? '';// 手机号
            $sex = $info['sex'] ?? '';// 性别
            if(empty($mobile) || empty($work_num)){
                throws('手机号或工号不能为空');
            }
            if(empty($real_name)){
                $real_name = $channel;
                if(empty($real_name)){
                    $real_name = $mobile;
                }
            }
            $sex_id = array_search($sex,$this->sex_arr);
            if($sex_id === false) $sex_id = 0;
            $save_data[$k]['sex_id'] = $sex_id;
//            // 判断工号是否已经存在
//            if(CompanyStaffBusiness::judgeExistWorkNum($company_id, $work_num)){
//                throws('工号[' . $work_num . ']已存在');
//            }
//            // 判断手机号是否已经存在
//            if(CompanyStaffBusiness::judgeExistMobile($company_id, $mobile)){
//                throws('手机号[' . $mobile . ']已存在');
//            }

            // 判断工号手机号是否已经存在
//            if(CompanyStaffBusiness::judgeExistWorkNumMobile($company_id, $work_num, $mobile)){
//                array_push($ExistWorkNumMobile,$save_data[$k]);
//                // throws('工号 + 手机号[' . $mobile . ']已存在');
//            }
            // 处理职位id
            $position_id = 0;
            if(!empty($position) && isset($positionArr[$position])) {
                $position_id = $positionArr[$position];
            }elseif(!empty($position)){
                $positionObj = CompanyPositionBusiness::firstOrCreate($company_id, $position);
                $position_id = $positionObj->id;
                $positionArr[$position] = $position_id;
            }
            $save_data[$k]['position_id'] = $position_id;

            // 处理部门id
            $department_id = 0;
            if(!empty($department) && isset($departmentArr[$department])) {
                $department_id = $departmentArr[$department];
            }elseif(!empty($department)){
                $departmentObj = CompanyDepartmentBusiness::firstOrCreateDepartment($company_id, $department);
                $department_id = $departmentObj->id;
                $departmentArr[$department] = $department_id;
            }
            $save_data[$k]['department_id'] = $department_id;

            // 处理小组id
            $group_id = 0;
            $groupKey = $department . '_' . $group;
            if(!empty($group) && isset($groupArr[$groupKey])) {
                $group_id = $groupArr[$groupKey];
            }elseif(!empty($group)){
                $groupObj = CompanyDepartmentBusiness::firstOrCreateGroup($company_id, $department_id, $group);
                $group_id = $groupObj->id;
                $groupArr[$groupKey] = $group_id;
            }
            $save_data[$k]['group_id'] = $group_id;

            // 处理渠道id
            $channel_id = 0;
//            $channelKey = $department . '_' . $group . '_' . $channel;
//            if(!empty($channel) && isset($channelArr[$channelKey])) {
//                $channel_id = $channelArr[$channelKey];
//            }elseif(!empty($channel)){
//                $channelObj = CompanyChannelBusiness::firstOrCreate($company_id, $department_id, $group_id, $channel);
//                $channel_id = $channelObj->id;
//                $channelArr[$channelKey] = $channel_id;
//            }
//            $save_data[$k]['channel_id'] = $channel_id;

            $temStaff = [
                'company_id' => $company_id,
                'admin_type' => $admin_type,
                'admin_username' => $mobile,
                'admin_password' => 'abc123456',
                'work_num' => $work_num,
                'department_id' => $department_id,
                'group_id' => $group_id,
                'channel_id' => $channel_id,
                'position_id' => $position_id,
                'real_name' => $real_name,
                'sex' => $sex_id,
                'mobile' => $mobile,
            ];
            $staffObj = CompanyStaffBusiness::firstOrCreate($company_id, $temStaff);
            $staff_id = $staffObj->id;

            // 判断版本号是否要+1
            $vStaffObj = null;
            $vStaffHistoryObj = null;
            CompanyStaffBusiness::compareHistoryOrUpdateVersion($vStaffObj , $vStaffHistoryObj, $company_id, $staff_id);

            // 保存员工管理渠道
            // CompanyStaffChannelBusiness::firstOrCreate($company_id, $department_id, $group_id, $channel_id, $staff_id);
        }
        return  okArray($ExistWorkNumMobile);

    }

    /**
     * 管理员转为员工
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
//    public function adminStaff(Request $request)
//    {
//        $this->InitParams($request);
//        $adminList = SiteAdmin::get();
//        $staffObj = null;
//        Common::getObjByModelName('CompanyStaff', $staffObj);
//        foreach($adminList as $admin){
//            $searchConditon = [
//                'company_id' => $admin->company_id,
//                'admin_type' => $admin->admin_type,
//                'admin_username' => $admin->admin_username,
//            ];
//            $updateFields = [
//                // 'admin_username' => $admin->admin_username,
//                'admin_password' => $admin->admin_password,
//                'real_name' => $admin->real_name,
//                'operate_staff_id' => $admin->operate_staff_id,
//                'operate_staff_history_id' => $admin->operate_staff_history_id,
//                'created_at' => $admin->created_at,
//                'updated_at' => $admin->updated_at,
//            ];
//            // $updateFields = $searchConditon;
//            $newStaffObj = null;
//            $newStaffObj = Common::firstOrCreate($staffObj, $searchConditon, $updateFields );
//            $staffHistoryObj = null;
//            // 获是员工历史记录id-- 操作员工
//            CompanyStaffBusiness::getHistoryStaff($newStaffObj , $staffHistoryObj, $newStaffObj->company_id, $newStaffObj->id );
//        }
//    }

    /**
     * getHistoryStaff
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function getHistoryStaff(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工

        // 获是员工历史记录id-- 操作员工
        $staffObj = null;
        //        Common::getObjByModelName("CompanyStaff", $staffObj);
        $staffHistoryObj = null;
        //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
        //        $StaffHistorySearch = [
        //            'company_id' => $company_id,
        //            'staff_id' => $staff_id,
        //        ];
        //
        //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

        // $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
        CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );
        return okArray($staffHistoryObj);
    }
}