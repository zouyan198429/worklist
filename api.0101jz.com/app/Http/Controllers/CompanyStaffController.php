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
     * 测试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        /* 查
        // $queryParams = [];
        $queryParams = [
            'where' => [
                // ['id', '&' , '4=4'],
                ['id', '=' , '4'],
                // ['company_id', $company_id],
                //['mobile', $keyword],
                //['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
            // 'orderBy' => ['id'=>'desc'],
        ];
        $relations = [];
        $requestData = CompanyStaffBusiness::getDataLimit(1, 2, 1, $queryParams , $relations);
        $total = $requestData['total'] ?? 0;
        $dataList = $requestData['dataList'] ?? [];
        $datInfo = $dataList[0] ?? [];// 具体数据对象
        if(!empty($datInfo)) {// 有记录，则修改数据
            $datInfo->real_name = $datInfo->real_name . 'aaaa';
            $datInfo->save();
            pr($datInfo->real_name);
        }else{
            pr('没有记录');
        }
        */

        // 增
        $dataParams = [
            'company_id' => 1,
            'admin_type' => 0,
            'admin_username' => '123456789',
            'admin_password' => 'abc123456',
            'work_num' => '011112',
            'department_id' => 1,
            'group_id' => 1,
            'channel_id' => 1,
            'position_id' => 1,
            'real_name' => '测试',
            'sex' => 1,
            'mobile' => '123456780',
        ];
        $obj = CompanyStaffBusiness::create($dataParams);
        if(!empty($obj)) {// 有记录，则修改数据
            $obj->real_name = $obj->real_name . 'aaaa';
            $obj->save();
            pr($obj->real_name);
        }else{
            pr('没有记录');
        }

    }

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
            // $staffObj = CompanyStaffBusiness::firstOrCreate($company_id, $temStaff);
            // $staff_id = $staffObj->id;
           // 查
            $queryParams = [
                'where' => [
                    // ['id', '&' , '4=4'],
                    ['company_id', $company_id],
                    ['mobile', '=' ,$mobile],
                    ['work_num', '=' ,$work_num],
                    //['mobile', $keyword],
                    //['admin_type',self::$admin_type],
                ],
    //            'select' => [
    //                'id','company_id','type_name','sort_num'
    //                //,'operate_staff_id','operate_staff_history_id'
    //                ,'created_at'
    //            ],
                // 'orderBy' => ['id'=>'desc'],
            ];
            $relations = [];
            $requestData = CompanyStaffBusiness::getDataLimit(1, 1, 1, $queryParams , $relations);
            // $total = $requestData['total'] ?? 0;
            $dataList = $requestData['dataList'] ?? [];
            $staffObj = $dataList[0] ?? [];// 具体数据对象
            if(!empty($staffObj)) {// 有记录，则修改数据
                foreach($temStaff as $t_k => $t_v){
                    if(in_array($t_k, ['admin_username', 'admin_password'])) continue; //不修改用户名和密码
                    $staffObj->{$t_k} = $t_v;
                }
                $staffObj->save();
            }else{// 没有记录,则新加
                $staffObj = CompanyStaffBusiness::create($temStaff);
            }
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


    /**
     * 通过id获得试题
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function getStaffByIds(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // $problem_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $ids = Common::get($request, 'ids');
        Common::judgeEmptyParams($request, 'ids', $ids);
        $relations = Common::get($request, 'relations');
        if(!empty($relations))   jsonStrToArr($relations , 1, '参数[relations]格式有误!');// json 转成数组

        // 获得员工信息
        $staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                // ['id', '1'],
                // ['phonto_name', 'like', '%知的标题1%']
            ],
            // 'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        if (strpos($ids, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $ids]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$ids);
        }

        $relations = ['staffDepartment', 'staffGroup', 'staffPosition'];
        $requestData = Common::getAllModelDatas($staffObj, $queryParams, $relations)->toArray();
        // 员工历史
        foreach($requestData as $k => $v){
            $staffId = $v['id'] ?? 0;
            $real_name = $v['real_name'] ?? '';
            // 类型名称
            $requestData[$k]['type_name'] = $v['answer_type']['type_name'] ?? '';
            if(isset($requestData[$k]['answer_type'])) unset($requestData[$k]['answer_type']);
            // 获得试题历史
            $temStaffObj = null;
            $staffHistoryObj = null;
            CompanyStaffBusiness::getHistoryStaff($temStaffObj,$staffHistoryObj, $company_id, $staffId);
            $staffHistoryId = $staffHistoryObj->id;
            if($staffHistoryId <= 0) throws('记录[' . $real_name . ']历史记录不存在');
            $requestData[$k]['staff_history_id'] = $staffHistoryId;
            $requestData[$k]['staff_id'] = $staffId;
            $requestData[$k]['now_staff'] = 0;// 最新的试题 0没有变化 ;1 已经删除  2 员工不同

            // 陪门名称
            $requestData[$k]['department_name'] = $v['staff_department']['department_name'] ?? '';
            // 小组名称
            $requestData[$k]['group_name'] = $v['staff_group']['department_name'] ?? '';

            // 职位
            $requestData[$k]['position_name'] = $v['staff_position']['position_name'] ?? '';
        }
        return okArray($requestData);
    }
}