<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyPositionBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyPosition';

    /**
     * 根据职位名称，获得职位信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param string $position_name 职位名称
     * @return mixed 职位对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate($company_id, $position_name){
        $positionObj = null;
        Common::getObjByModelName(self::$model_name, $positionObj);
        $searchConditon = [
            'company_id' => $company_id,
            'position_name' => $position_name,
        ];
        $updateFields = [
        ];
        Common::firstOrCreate($positionObj, $searchConditon, $updateFields );
        return $positionObj;
    }

}
