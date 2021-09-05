<?php
// 工单
namespace App\Business;


use App\Models\Company;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyBusiness extends BaseBusiness
{
    protected static $model_name = 'Company';

    /**
     * 获得 id=> 键值对
     *
     * @param int $company_id 公司id
     * @param int $is_kv 操作说明
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getCompanyKeyVals($company_id, $is_kv = true){
        $customerTypeList = Company::select(['id', 'company_name'])
            ->orderBy('id', 'desc')// ->orderBy('sort_num', 'desc')
//            ->where([
//                ['company_id', '=', $company_id],
//            ])
            ->get()->toArray();
        if(!$is_kv) return $customerTypeList;
        return Tool::formatArrKeyVal($customerTypeList, 'id', 'company_name');

    }

}
