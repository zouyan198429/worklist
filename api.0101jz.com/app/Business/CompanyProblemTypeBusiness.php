<?php
// 工单
namespace App\Business;

use App\Models\CompanyProblemType;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyProblemTypeBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyProblemType';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $type_parent_id 父id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkTypeKeyVals($company_id, $type_parent_id = 0, $is_kv = true){
        $workFirstList = CompanyProblemType::select(['id', 'type_name'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
                ['type_parent_id', '=', $type_parent_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $workFirstList;
        return Tool::formatArrKeyVal($workFirstList, 'id', 'type_name');
    }

}
