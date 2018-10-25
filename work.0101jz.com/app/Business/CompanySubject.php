<?php
// 试题
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanySubject extends BaseBusiness
{
    protected static $model_name = 'CompanySubject';

    // 题目类型1单选；2多选；4判断
    public static $selTypes = [
        '1' => '单选',
        '2' => '多选',
        '4' => '判断',
    ];
    // 判断题答案
    public static $answerJudge = [
        '0' => '错',
        '1' => '对',
    ];
    public static $answerSplit = '||->'; //答案分隔符

    public static $orderKeys = [
        ['key' => 'sort_num', 'sort' => 'desc', 'type' => 'numeric'],
        ['key' => 'id', 'sort' => 'asc', 'type' => 'numeric'],
    ];
    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        // $params = self::formatListParams($request, $controller, $queryParams);

        $subject_type = Common::getInt($request, 'subject_type');
        if($subject_type > 0){
            array_push($queryParams['where'],['subject_type', $subject_type]);
        }

        $type_id = Common::getInt($request, 'type_id');
        if($type_id > 0){
            array_push($queryParams['where'],['type_id', $type_id]);
        }

        $title = Common::get($request, 'title');
        if(!empty($title)){
            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
        }

        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
        if (!empty($ids)) {
            if (strpos($ids, ',') === false) { // 单条
                array_push($queryParams['where'],['id', $ids]);
            }else{
                $queryParams['whereIn']['id'] = explode(',',$ids);
            }
        }
        $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
        if($isExport == 1) $oprateBit = 1;
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 添加人
            $data_list[$k]['real_name'] = $v['oprate_staff_history']['real_name'] ?? '';
            if(isset($data_list[$k]['oprate_staff_history'])) unset($data_list[$k]['oprate_staff_history']);
            // 类型名称
            $data_list[$k]['type_name'] = $v['answer_type']['type_name'] ?? '';
            if(isset($data_list[$k]['answer_type'])) unset($data_list[$k]['answer_type']);
            // 答案信息
            self::formatAnswer($request, $controller, $data_list[$k], $isExport);
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
            $headArr = ['type_text'=>'类型', 'type_name'=>'分类', 'title'=>'题目', 'answer_txt'=>'答案'];
            ImportExport::export('','题目',$data_list,1, $headArr, 0, ['sheet_title' => '题目']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
    }
    /**
     * 格式化 答案信息
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param object $subjectInfo 试题信息
     * @param int $isExport 是否导出 0非导出 ；1导出数据
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    //
    // $subjectInfo
    public static function formatAnswer(Request $request, Controller $controller, &$subjectInfo, $isExport = 0){

        if(isset($subjectInfo['subject_answer']) && count($subjectInfo['subject_answer']) > 0){
            $subject_answer = $subjectInfo['subject_answer'];
            $orderKeys = self::$orderKeys;
            $subject_answer = Tool::php_multisort($subject_answer, $orderKeys);
            $subjectInfo['subject_answer'] = $subject_answer;
            $answers = [];
            $rightAnswers = [];
            $key = ord("A");
            $answerSplit = self::$answerSplit; //分隔符
            foreach($subject_answer as $an_k => $answer){
                $colum = chr($key);
                if($isExport == 1){// 导出
                    array_push($answers, $answer['answer_content'] . $answerSplit . ($answer['is_right'] == 1 ? '√' : '×') );
                }else{
                    array_push($answers, $colum . '、' .$answer['answer_content'] . '   ' . ($answer['is_right'] == 1 ? '<span class="right">√</span>' : '<span class="wrong">×</span>') );
                }
                if($answer['is_right'] == 1) array_push($rightAnswers, $colum);
                $subjectInfo['subject_answer'][$an_k]['colum'] = $colum;
                $key += 1;
            }
            $subjectInfo['answer_right'] = implode('、', $rightAnswers);

            if($isExport == 1) {// 导出
                $subjectInfo['answer_txt'] = implode(PHP_EOL, $answers);
            }else{
                $subjectInfo['answer_txt'] = implode('<br/>', $answers);
            }
            // unset($subjectInfo['subject_answer']);
        }
        $subject_type = $subjectInfo['subject_type'] ?? '';
        $answer = $subjectInfo['answer'] ?? '';
        if($subject_type == 4 && in_array($answer,array_keys(self::$answerJudge))){
            if($isExport == 1) {// 导出
                $answerTxt = ($answer == 1) ? '√' : '×';
            }else{
                $answerTxt = ($answer == 1) ? '<span class="right">√</span>' : '<span class="wrong">×</span>';
            }
            $subjectInfo['answer_txt'] = $answerTxt;
            $subjectInfo['answer_right'] = $answerTxt;
        }
        return $subjectInfo;
    }

    /**
     * 格式化列表查询条件-暂不用
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $queryParams 条件数组/json字符
     * @return  array 参数数组 一维数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function formatListParams(Request $request, Controller $controller, &$queryParams = []){
//        $params = [];
//        $title = Common::get($request, 'title');
//        if(!empty($title)){
//            $params['title'] = $title;
//            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
//        }
//
//        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
//        if (!empty($ids)) {
//            $params['ids'] = $ids;
//            if (strpos($ids, ',') === false) { // 单条
//                array_push($queryParams['where'],['id', $ids]);
//            }else{
//                $queryParams['whereIn']['id'] = explode(',',$ids);
//                $params['idArr'] = explode(',',$ids);
//            }
//        }
//        return $params;
//    }

    /**
     * 获得当前记录前/后**条数据--二维数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $nearType 类型 1:前**条[默认]；2后**条 ; 4 最新几条;8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
     * @param int $limit 数量 **条
     * @param int $offset 偏移数量
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据 - 二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearList(Request $request, Controller $controller, $id = 0, $nearType = 1, $limit = 1, $offset = 0, $queryParams = [], $relations = '', $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 前**条[默认]
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
//                ['id', '>', $id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'asc'],
            'limit' => $limit,
            'offset' => $offset,
            // 'count'=>'0'
        ];
        if(($nearType & 1) == 1){// 前**条
            $defaultQueryParams['orderBy'] = ['id'=>'asc'];
            array_push($defaultQueryParams['where'],['id', '>', $id]);
        }

        if(($nearType & 2) == 2){// 后*条
            array_push($defaultQueryParams['where'],['id', '<', $id]);
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 4) == 4){// 4 最新几条
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 8) == 8){// 8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
            $defaultQueryParams['count'] = 0;
        }

        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, $notLog);
        // 格式化数据
        $data_list = $result['result']['data_list'] ?? [];
        if($nearType == 1) $data_list = array_reverse($data_list); // 相反;
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['result']['data_list'] = $data_list;
        return $data_list;
    }

    /**
     * 导入模版
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function importTemplate(Request $request, Controller $controller)
    {
        $headArr = ['type_text'=>'类型', 'type_name'=>'分类', 'title'=>'题目', 'answer_txt'=>'答案'];
        $data_list = [];
        ImportExport::export('','试题导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '试题导入模版']);
        die;
    }
    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller)
    {
        return self::delAjaxBase($request, $controller, self::$model_name);

    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $relations = ''){
        $company_id = $controller->company_id;
        // $relations = '';
        $resultDatas = CommonBusiness::getinfoApi(self::$model_name, $relations, $company_id , $id);
        // $resultDatas = self::getInfoDataBase($request, $controller, self::$model_name, $id, $relations);
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );

        // 答案信息
        $answers = [];
        if(isset($resultDatas['subject_answer']) && count($resultDatas['subject_answer']) > 0 ){
            $subject_answer = $resultDatas['subject_answer'];
            $orderKeys = self::$orderKeys;
            $subject_answer = Tool::php_multisort($subject_answer, $orderKeys);
            $key = ord("A");
            $answerSplit = self::$answerSplit; //分隔符
            foreach($subject_answer as $answer){
                $colum = chr($key);
                $temArr = [
                    'id' => $answer['id'],
                    'colum' => $colum,
                    'answer_content' => $answer['answer_content'],
                    'is_right' => $answer['is_right'],
                ];
                array_push($answers, $temArr);
                $key += 1;
            }
            // unset($resultDatas['subject_answer']);
        }
        $resultDatas['answer_list'] = $answers;

        // 类型名称
        $resultDatas['type_name'] = $resultDatas['answer_type']['type_name'] ?? '';
        if(isset($resultDatas['answer_type'])) unset($resultDatas['answer_type']);

        return $resultDatas;
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            if($modifAddOprate) self::addOprate($request, $controller, $saveData);

        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function saveById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            // if($modifAddOprate) self::addOprate($request, $controller, $saveData);
        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            // self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        // return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);

        // 参数
        $requestData = [
            // 'id' => $id,
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.saveSubject');
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        $result = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
        if($id <= 0){
            $id = $result['id'] ?? 0;
        }
        return $result;
    }
}
