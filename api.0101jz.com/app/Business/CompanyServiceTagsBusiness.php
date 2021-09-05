<?php
// 工单
namespace App\Business;


use App\Models\CompanyServiceTags;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyServiceTagsBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyServiceTags';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getServiceTagsKeyVals($company_id, $is_kv = true){
        $serviceTagList = CompanyServiceTags::select(['id', 'tag_name'])
            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
            ])
            ->get()->toArray();
        if(!$is_kv) return $serviceTagList;
        return Tool::formatArrKeyVal($serviceTagList, 'id', 'tag_name');

    }

}
