<?php
// 工单
namespace App\Business;


use App\Models\Company;
use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
use Illuminate\Support\Facades\DB;

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

    /**
     * 跑过期脚本 更新过期 ，快到期
     *
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoCompanyStatusMsg(){
        DB::beginTransaction();
        try {
            // 公司状态;1新注册2试用客户4VIP 8VIP 将过期  16过期会员32过期试用
            $focusWhere = [
                ['open_status', '=', 1],
               // ['is_focus', '=', 0],
                // ['status', '<>', 8],
                ['company_vipend', '<=', Carbon::now()->toDateTimeString()],
                // ['expiry_time', '<=', Carbon::now()->addMinutes(self::$focusTime)],
            ];
            $overDataList = Company::select(['id', 'company_status'])->where($focusWhere)->whereIn('company_status',[1,2,4,8])->get()->toArray();;
            if(!empty($overDataList)){
                $overFormatDataList = Tool::arrUnderReset($overDataList, 'company_status', 2);
                foreach($overFormatDataList as $t_k => $t_list){
                    $new_company_status = 32;
                    $operateIds = array_values(array_unique(array_column($t_list, 'id')));
                    if(!in_array($t_k, [1,2])){// 32过期试用
                        $new_company_status = 32;
                    }else{// 16过期会员
                        $new_company_status = 16;
                    }
                    if(!empty($operateIds)) Company::whereIn('id', $operateIds)->update(['company_status' => $new_company_status]);// ->where($focusWhere)

                }
            }
            // 对vip会员，进入将过期
            $focusWhere = [
                ['open_status', '=', 1],
                ['company_status', '=', 4],
                // ['is_focus', '=', 0],
                // ['status', '<>', 8],
                // ['company_vipend', '<=', Carbon::now()->toDateTimeString()],
                 ['company_vipend', '<=', Carbon::now()->addMonth(1)],
            ];
            $overDataList = Company::select(['id', 'company_status'])->where($focusWhere)->get()->toArray();;// ->whereIn('company_status',[1,2,4,8])
            if(!empty($overDataList)){
                $operateIds = array_values(array_unique(array_column($overDataList, 'id')));
                if(!empty($operateIds)) Company::whereIn('id', $operateIds)->update(['company_status' => 8]);// ->where($focusWhere)
            }

        } catch ( \Exception $e) {
            DB::rollBack();
            throws('工单即将逾期提醒失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();

    }
}
