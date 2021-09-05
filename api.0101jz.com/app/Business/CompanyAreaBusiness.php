<?php
// 工单
namespace App\Business;


use App\Models\CompanyArea;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyAreaBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyArea';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $area_parent_id 父id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkTypeKeyVals($company_id, $area_parent_id = 0, $is_kv = true){

        $areaCityList = CompanyArea::select(['id', 'area_name'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
                ['area_parent_id', '=', $area_parent_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $areaCityList;
        return Tool::formatArrKeyVal($areaCityList, 'id', 'area_name');
    }

}
