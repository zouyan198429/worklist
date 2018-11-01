<?php
// 渠道[一级分类]
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanySubjectTypeBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanySubjectType';

    /**
     * 根据渠道名称，获得渠道信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param string $channel_name 分类名称
     * @param array $updateFields  其它字段一维数组, ['字段' => '字段值']
     * @return mixed 职位对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate($company_id, $type_name, $updateFields){
        $mainObj = null;
        Common::getObjByModelName(self::$model_name, $mainObj);
        $searchConditon = [
            'company_id' => $company_id,
            'type_name' => $type_name,
        ];
//        $updateFields = [
//        ];
        Common::firstOrCreate($mainObj, $searchConditon, $updateFields );
        return $mainObj;
    }

}
