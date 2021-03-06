<?php
// 渠道[一级分类]
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyChannelBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyChannel';

    /**
     * 根据渠道名称，获得渠道信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param string $channel_name 渠道名称
     * @return mixed 职位对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate($company_id, $department_id, $group_id, $channel_name){
        $channelObj = null;
        Common::getObjByModelName(self::$model_name, $channelObj);
        $searchConditon = [
            'company_id' => $company_id,
            'department_id' => $department_id,
            'group_id' => $group_id,
            'channel_name' => $channel_name,
        ];
        $updateFields = [
        ];
        Common::firstOrCreate($channelObj, $searchConditon, $updateFields );
        return $channelObj;
    }

}
