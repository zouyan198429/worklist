<?php
// 员工管理渠道
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyStaffChannelBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyStaffChannel';

    /**
     * 根据渠道名称，获得渠道信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param int $channel_id 渠道id
     * @param int $staff_id 员工id
     * @return mixed 职位对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate($company_id, $department_id, $group_id, $channel_id, $staff_id){
        $Obj = null;
        Common::getObjByModelName(self::$model_name, $Obj);
        $searchConditon = [
            'company_id' => $company_id,
            'department_id' => $department_id,
            'group_id' => $group_id,
            'channel_id' => $channel_id,
            'staff_id' => $staff_id,
        ];
        $updateFields = [
        ];
        Common::firstOrCreate($Obj, $searchConditon, $updateFields );
        return $Obj;
    }

}
