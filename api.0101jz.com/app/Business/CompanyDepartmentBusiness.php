<?php
// 工单
namespace App\Business;


use App\Models\CompanyDepartment;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyDepartmentBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyDepartment';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $department_parent_id 父id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getDepartmentKeyVals($company_id, $department_parent_id = 0, $is_kv = true){
        $departmentFirstList = CompanyDepartment::select(['id', 'department_name'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
                ['department_parent_id', '=', $department_parent_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $departmentFirstList;
        return Tool::formatArrKeyVal($departmentFirstList, 'id', 'department_name');

    }

    /**
     * 根据部门名称，获得部门信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param string $department_name 部门名称
     * @return mixed 部门对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreateDepartment($company_id, $department_name){
        $departmentObj = null;
        Common::getObjByModelName(self::$model_name, $departmentObj);
        $searchConditon = [
            'company_id' => $company_id,
            'department_name' => $department_name,
        ];
        $updateFields = [
        ];
        Common::firstOrCreate($departmentObj, $searchConditon, $updateFields );
        return $departmentObj;
    }

    /**
     * 根据小组名称，获得小组信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param int $department_id 部门id
     * @param string $group_name 小组名称
     * @return mixed 部门对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreateGroup($company_id, $department_id, $group_name){
        $groupObj = null;
        Common::getObjByModelName(self::$model_name, $groupObj);
        $searchConditon = [
            'company_id' => $company_id,
            'department_parent_id' => $department_id,
            'department_name' => $group_name,
        ];
        $updateFields = [
        ];
        Common::firstOrCreate($groupObj, $searchConditon, $updateFields );
        return $groupObj;
    }
}
