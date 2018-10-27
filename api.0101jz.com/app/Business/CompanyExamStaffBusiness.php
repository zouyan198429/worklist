<?php
// 考试员工
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyExamStaffBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyExamStaff';


    /**
     * 根据条件，获得试题信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param array $infoArr  信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 对象
     * @author zouyan(305463219@qq.com)
     */
//    public static function firstOrCreate($company_id, $infoArr){
//        $obj = null;
//        Common::getObjByModelName(self::$model_name, $obj);
//        $searchConditon = [
//            'company_id' => $company_id,
////            'work_num' => $infoArr['work_num'] ?? '',
////            'mobile' => $infoArr['mobile'] ?? '',
//        ];
//        $updateFields = $infoArr;
//        Common::firstOrCreate($obj, $searchConditon, $updateFields );
//        return $obj;
//    }


    /**
     * 根据条件，获得试题信息[有则更新;没有则新加]
     *
     * @param int $company_id 公司id
     * @param int $exam_id 考次id
     * @param int $staff_id 员工id
     * @param array $infoArr  信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 对象
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreate($company_id, $exam_id, $staff_id, $infoArr){

        // 保存或修改反馈问题
        $obj = null;
        Common::getObjByModelName(self::$model_name, $obj);
        $searchConditon = [
            'company_id' => $company_id,
            'exam_id' => $exam_id,
            'staff_id' => $staff_id,
        ];
        Common::updateOrCreate($obj, $searchConditon, $infoArr );
        return $obj;
    }
}
