<?php
// 考试员工
namespace App\Business;


use App\Models\Company;
use App\Models\CompanyExamStaff;
use App\Models\CompanyExamStaffSubject;
use App\Models\CompanyExam;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class CompanyExamStaffBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyExamStaff';


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
     * @param int $exam_id 考次id
     * @param int $staff_id 员工id
     * @param array $infoArr  信息 ['work_num' =>'fsdfsd', 'mobile' => '15829686962']
     * @return mixed 对象
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreate($company_id, $exam_id, $staff_id, $infoArr){

        // 保存或修改反馈问题
        $obj = null;
        Common::getObjByModelName(self::$model_name, $obj);
        $searchConditon = [
            'company_id' => $company_id,
            'exam_id' => $exam_id,
            'staff_id' => $staff_id,
        ];
        Common::updateOrCreate($obj, $searchConditon, $infoArr );
        return $obj;
    }

    /**
     * 跑过期脚本
     *
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoExamStaff(){
        DB::beginTransaction();
        try {
            // $currentNow = Carbon::now();
            // 开考-更新考试为考试中
            $beginWhere = [
                ['status', '=', 1],
                ['exam_begin_time', '<=', Carbon::now()->toDateTimeString()],
                ['exam_end_time', '>=', Carbon::now()->toDateTimeString()],
            ];
            $updateData = [
                'status'=> 2
            ];
            CompanyExam::where($beginWhere)->update($updateData);
            // 开考-更新考试为 已考试
            $beginWhere = [
                ['status', '=', 2],
                ['exam_end_time', '<=', Carbon::now()->toDateTimeString()],
            ];
            $updateData = [
                'status'=> 3
            ];
            CompanyExam::where($beginWhere)->update($updateData);
//            // 开考-更新考试为考试中
//            $beginWhere = [
//                ['status', '=', 1],
//                ['exam_begin_time', '<=', Carbon::now()->toDateTimeString()],
//                ['exam_end_time', '>=', Carbon::now()->toDateTimeString()],
//            ];
//            $updateData = [
//                'status'=> 2
//            ];
//            CompanyExamStaff::where($beginWhere)->update($updateData);
            //自动完成考试 -- 未考试的，改为缺考
//            $beginWhere = [
//                ['status', '=', 1],
//                ['exam_end_time', '<=', Carbon::now()->toDateTimeString()],
//            ];
//            $updateData = [
//                'status'=> 4,
//                'is_pass'=> 3
//            ];
//            CompanyExamStaff::where($beginWhere)->update($updateData);

            //考试时间到自动提交试卷
            $endWhere = [
                // ['status', '=', 2],
                ['exam_end_time', '<=', Carbon::now()->toDateTimeString()],
            ];
            $examList = CompanyExamStaff::where($endWhere)->whereIn('status', [1,2])->get();
            foreach($examList as $exam){
                $subject_history_ids = $exam->subject_history_ids;
                // 缺考
                if(empty($subject_history_ids) && $exam->status = 1){
                    $exam->status = 4;
                    $exam->is_pass = 3;
                    $exam->save();
                    continue;
                }

                // 获得考次详情
                // 缓存
                $cachePre = 'company_exam' ;// __FUNCTION__;// 缓存前缀
                $cacheKey = '';// 缓存键[没算前缀]
                $paramKeyValArr = ['pass_score', $exam->exam_id];//[$company_id, $operate_no];// 关键参数  $request->input()
                $cacheResult = Tool::getCacheData($cachePre,$cacheKey, $paramKeyValArr,2, 1);
                if($cacheResult !== false) {
                    $pass_score =  $cacheResult;
                } else {
                    $examInfo = CompanyExam::find($exam->exam_id);
                    if(empty($examInfo))  throws('考次信息不存在!');
                    $pass_score = $examInfo['pass_score'];
                    // 缓存当前试题历史id数据 60分钟
                    Tool::cacheData($cachePre, $cacheKey, $pass_score, 60*60, 2);
                }

                // 自动计算成绩
                $searchConditon = [
                    'exam_id' => $exam->exam_id,// 考次id
                    'exam_staff_id' => $exam->id,// 考次人员id
                    'paper_id' => $exam->paper_id,// 试卷id
                    'paper_history_id' => $exam->paper_history_id,// 试卷历史id
                ];
//                $countField = [
//                    DB::raw('SUM(score) as exam_results'),
//                    // DB::raw('SUM(order_count) as order_count')
//                ];
                // $result = CompanyExamStaffSubject::where($searchConditon)->first($countField)->toArray();
                $exam_results = CompanyExamStaffSubject::where($searchConditon)->sum('score');

                // 保存记录
                //exam_results 考试成绩 ;status 状态1等考试2考试中3已考试4缺考;
                //is_pass  是否及格0待考1未及格2及格  answer_end_time 结束答题时间
                $saveData= [
                    'exam_results' => $exam_results,// 考试成绩
                    'status' => 3,// 状态1等考试2考试中3已考试4缺考;
                    'is_pass' => ($exam_results >= $pass_score) ? 2 : 1,// 是否及格0待考1未及格2及格
                    'answer_end_time' => date('Y-m-d H:i:s'),// 结束答题时间
                ];
                foreach($saveData as $field => $fv){
                    $exam->{$field} = $fv;
                }
                $exam->save();
            }

        } catch ( \Exception $e) {
            DB::rollBack();
            throws('考试自动操作失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
    }

}
