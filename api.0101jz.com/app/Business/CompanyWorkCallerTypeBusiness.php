<?php
// 工单
namespace App\Business;


use App\Models\CompanyWorkCallerType;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyWorkCallerTypeBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWorkCallerType';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkCallerTypeKeyVals($company_id, $is_kv = true){
        $workCallTypeList = CompanyWorkCallerType::select(['id', 'type_name'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $workCallTypeList;
        return Tool::formatArrKeyVal($workCallTypeList, 'id', 'type_name');

    }

}
