<?php
// 试题答案
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanySubjectAnswerBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanySubjectAnswer';

    /**
     * 试题答案获得主表+历史表对象
     *
     * @param obj $obj 主对象
     * @param obj $historyObj 历史对象
     * @param int $company_id 公司id
     * @param int $main_id 试题答案id
     * @return int 试题答案历史记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryStaff(&$obj = null , &$historyObj = null, $company_id = 0, $main_id = 0 ){

        // 获是试题历史记录id
        //$obj = null;
        Common::getObjByModelName("CompanySubjectAnswer", $obj);
        // $historyObj = null;
        Common::getObjByModelName("CompanySubjectAnswerHistory", $historyObj);
        $historySearch = [
            'company_id' => $company_id,
            'answer_id' => $main_id,
        ];

        Common::getHistory($obj, $main_id, $historyObj,'company_subject_answer_history', $historySearch, []);
        $history_id = $historyObj->id;
        return $history_id;

    }

    /**
     * 试题答案获得主表+历史表对象
     *
     * @param obj $obj 主对象
     * @param obj $historyObj 历史对象
     * @param int $company_id 公司id
     * @param int $main_id 试题答案id
     * @return int 试题答案历史记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersion(&$obj = null , &$historyObj = null, $company_id = 0, $main_id = 0){
        // 判断版本号是否要+1
        //$obj = null;
        Common::getObjByModelName("CompanySubjectAnswer", $obj);

        $historyObj = null;
        Common::getObjByModelName("CompanySubjectAnswerHistory", $historyObj);
        $historySearch = [
            'company_id' => $company_id,
            'answer_id' => $main_id,
        ];

        $ignoreFields = ['answer_id'];
        $diffDataArr = Common::compareHistoryOrUpdateVersion($obj, $main_id,
            $historyObj,'company_subject_answer_history',
            $historySearch, $ignoreFields, 0);
        if(! empty($diffDataArr)){// 客户有新信息，版本号+1
            // 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
            $obj->version_num++ ;
            $obj->save();
        }
    }

    /**
     * 根据条件，获得试题信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param array $infoArr  信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 对象
     * @author zouyan(305463219@qq.com)
     */
//    public static function firstOrCreate($company_id, $infoArr){
//        $obj = null;
//        Common::getObjByModelName(self::$model_name, $obj);
//        $searchConditon = [
//            'company_id' => $company_id,
////            'work_num' => $infoArr['work_num'] ?? '',
////            'mobile' => $infoArr['mobile'] ?? '',
//        ];
//        $updateFields = $infoArr;
//        Common::firstOrCreate($obj, $searchConditon, $updateFields );
//        return $obj;
//    }


    /**
     * 根据条件，获得试题信息[有则更新;没有则新加]
     *
     * @param int $company_id 公司id
     * @param int $subject_id 试题id
     * @param int $answer_val 值
     * @param array $infoArr  信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 对象
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreate($company_id, $subject_id, $answer_val, $infoArr){

        // 保存或修改反馈问题
        $obj = null;
        Common::getObjByModelName(self::$model_name, $obj);
        $searchConditon = [
            'company_id' => $company_id,
            'subject_id' => $subject_id,
            'answer_val' => $answer_val,
        ];
        Common::updateOrCreate($obj, $searchConditon, $infoArr );
        return $obj;
    }
}
