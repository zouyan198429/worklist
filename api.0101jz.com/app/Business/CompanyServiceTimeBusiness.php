<?php
// 工单
namespace App\Business;

use App\Models\CompanyServiceTime;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyServiceTimeBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyServiceTime';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getServiceTimeKeyVals($company_id, $is_kv = true){
        $serviceTimeList = CompanyServiceTime::select(['id', 'time_name', 'second_num'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $serviceTimeList;
       return Tool::formatArrKeyVal($serviceTimeList, 'id', 'time_name');
    }

}
