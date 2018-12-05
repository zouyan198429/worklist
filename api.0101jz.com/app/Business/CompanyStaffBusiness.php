<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyStaffBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyStaff';


    /**
     * 员工获得主表+历史表对象
     *
     * @param obj $workObj 员工主对象
     * @param obj $staffHistoryObj 员工历史对象
     * @param int $company_id 公司id
     * @param int $staff_id 员工id
     * @return int 员工历史记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryStaff(&$staffObj = null , &$staffHistoryObj = null, $company_id = 0, $staff_id = 0 ){

        // 获是员工历史记录id-- 操作员工
        //$staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        // $staffHistoryObj = null;
        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
        $StaffHistorySearch = [
            'company_id' => $company_id,
            'staff_id' => $staff_id,
        ];

        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);
        $operate_staff_history_id = $staffHistoryObj->id;
        return $operate_staff_history_id;

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
        Common::getObjByModelName("CompanyStaff", $obj);

        $historyObj = null;
        Common::getObjByModelName("CompanyStaffHistory", $historyObj);
        $historySearch = [
            'company_id' => $company_id,
            'staff_id' => $main_id,
        ];

        $ignoreFields = ['staff_id'];
        $diffDataArr = Common::compareHistoryOrUpdateVersion($obj, $main_id,
            $historyObj,'company_staff_history',
            $historySearch, $ignoreFields, 0);
        if(! empty($diffDataArr)){// 客户有新信息，版本号+1
            // 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
            $obj->version_num++ ;
            $obj->save();
        }
    }

    /**
     * 判断工号是否已经存在
     *
     * @param int $company_id 公司id
     * @param string $work_num 工号
     * @return boolean true ：已存在 ;false：不存在
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeExistWorkNum($company_id, $work_num){
        $staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        $queryParams = [
            'where' => [
                ['company_id', '=', $company_id],
                ['work_num', '=', $work_num],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'select' => [
                'id'
            ],
           // 'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        $relations = '';
        $requestData = Common::getModelListDatas($staffObj, 1, 1, 1, $queryParams, $relations);
        $dataList = $requestData['dataList'] ?? [];
        if(count($dataList) > 0) return true;
        return false;
    }

    /**
     * 判断手机号是否已经存在
     *
     * @param int $company_id 公司id
     * @param string $mobile 手机号
     * @return boolean true ：已存在 ;false：不存在
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeExistMobile($company_id, $mobile){
        $staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        $queryParams = [
            'where' => [
                ['company_id', '=', $company_id],
                ['mobile', '=', $mobile],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'select' => [
                'id'
            ],
            // 'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        $relations = '';
        $requestData = Common::getModelListDatas($staffObj, 1, 1, 1, $queryParams, $relations);
        $dataList = $requestData['dataList'] ?? [];
        if(count($dataList) > 0) return true;
        return false;
    }

    /**
     * 判断手机号是否已经存在
     *
     * @param int $company_id 公司id
     * @param string $work_num 工号
     * @param string $mobile 手机号
     * @return boolean true ：已存在 ;false：不存在
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeExistWorkNumMobile($company_id, $work_num, $mobile){
        $staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        $queryParams = [
            'where' => [
                ['company_id', '=', $company_id],
                ['work_num', '=', $work_num],
                ['mobile', '=', $mobile],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'select' => [
                'id'
            ],
            // 'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        $relations = '';
        $requestData = Common::getModelListDatas($staffObj, 1, 1, 1, $queryParams, $relations);
        $dataList = $requestData['dataList'] ?? [];
        if(count($dataList) > 0) return true;
        return false;
    }

    /**
     * 根据工号，获得员工信息[没有则新加]
     *
     * @param int $company_id 公司id
     * @param array $staffArr  员工信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 员工对象
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate($company_id, $staffArr){
        $staffObj = null;
        Common::getObjByModelName(self::$model_name, $staffObj);
        $searchConditon = [
            'company_id' => $company_id,
            'work_num' => $staffArr['work_num'] ?? '',
            'mobile' => $staffArr['mobile'] ?? '',
        ];
        $updateFields = $staffArr;
        Common::firstOrCreate($staffObj, $searchConditon, $updateFields );
        return $staffObj;
    }

    /**
     * 获得指定条件的多条数据
     *
     * @param int 选填 $page 当前页page [默认1]
     * @param int 选填 $pagesize 每页显示的数量 [默认10]
     * @param int 选填 $total 总记录数,优化方案：传<=0传重新获取总数[默认0];=-5:只统计条件记录数量，不返回数据
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return array 数据
        $listData = [
            'pageSize' => $pagesize,
            'page' => $page,
            'total' => $total,
            'totalPage' => ceil($total/$pagesize),
            'dataList' => $requestData,
        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function getDataLimit($page = 1, $pagesize = 10, $total = 0, $queryParams = [], $relations = []){
        return self::__getDataLimit(self::$model_name,$page, $pagesize, $total, $queryParams, $relations);
    }

    /**
     * 新加
     *
     * @param array  $dataParams 新加的数据
     * @return object 对象
     * @author zouyan(305463219@qq.com)
     */
    public static function create($dataParams = [])
    {
        return self::__create(self::$model_name, $dataParams);
    }
}
