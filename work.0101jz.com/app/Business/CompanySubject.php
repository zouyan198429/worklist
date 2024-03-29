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
     * @param array $extParams 其它扩展参数，
     *    $extParams = [
     *        'useQueryParams' => '是否用来拼接查询条件，true:用[默认];false：不用'
     *   ];
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $extParams = [], $notLog = 0){
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
        $isExport = 0;

        $useSearchParams = $extParams['useQueryParams'] ?? true;// 是否用来拼接查询条件，true:用[默认];false：不用
        if($useSearchParams) {
            // $params = self::formatListParams($request, $controller, $queryParams);

            $subject_type = Common::getInt($request, 'subject_type');
            if ($subject_type > 0) {
                array_push($queryParams['where'], ['subject_type', $subject_type]);
            }

            $type_id = Common::getInt($request, 'type_id');
            if ($type_id > 0) {
                array_push($queryParams['where'], ['type_id', $type_id]);
            }

            $title = Common::get($request, 'title');
            if (!empty($title)) {
                array_push($queryParams['where'], ['title', 'like', '%' . $title . '%']);
            }

            $ids = Common::get($request, 'ids');// 多个用逗号分隔,
            if (!empty($ids)) {
                if (strpos($ids, ',') === false) { // 单条
                    array_push($queryParams['where'], ['id', $ids]);
                } else {
                    $queryParams['whereIn']['id'] = explode(',', $ids);
                }
            }
            $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
            if ($isExport == 1) $oprateBit = 1;
        }
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
            $headArr = ['id'=>'记录id', 'type_text'=>'类型', 'type_name'=>'分类', 'title'=>'题目', 'answer_right'=>'答案'];// 'answer_txt'=>'答案'
            $key = ord("A");
            for($i = 1;$i <= 6 ; $i++){
                $colum = chr($key);
                $headArr[$colum] = '答案' . $colum . '内容';
                $key += 1;
            }
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
                $subjectInfo[$colum] = $answer['answer_content'];
                $key += 1;
            }
            for($i = $key; $i <= ord("F"); $i++){
                $colum = chr($i);
                $subjectInfo[$colum] = '';
            }

            $subjectInfo['answer_right'] = implode('、', $rightAnswers);

            if($isExport == 1) {// 导出
                $subjectInfo['answer_txt'] = implode(PHP_EOL, $answers);
            }else{
                $subjectInfo['answer_txt'] = implode('<br/>', $answers);
            }
            // unset($subjectInfo['subject_answer']);
        } else {
            $key = ord("A");
            for($i = $key; $i <= ord("F"); $i++){
                $colum = chr($i);
                $subjectInfo[$colum] = '';
            }
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
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, [], $notLog);
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
        $headArr = ['id'=>'记录id', 'type_text'=>'类型', 'type_name'=>'分类', 'title'=>'题目', 'answer_right'=>'答案'];// , 'answer_txt'=>'答案'
        $data_list = [];
        $itemData = [
            'id'=>'0或空:新加;' . PHP_EOL . '数字：更新指定记录试题,如果不存在，则不能导入，此时应该用新加(0值)',
            'type_text'=>'填：单选、多选、判断',
            'type_name'=>'如系统没有，则会自动新加',
            'title'=>'题目',
            'answer_right'=>'格式：' . PHP_EOL . '单选:A;' . PHP_EOL . '多选：A、C；' . PHP_EOL . '判断：√或×；'
        ];
        $key = ord("A");
        for($i = 1;$i <= 6 ; $i++){
            $colum = chr($key);
            $headArr[$colum] = '答案' . $colum . '内容';
            $itemData[$colum] = '';
            $key += 1;
        }
        $itemData['A'] = '具体答案(可为空，从A-F，遇到第一个空，后面的自动忽略)';
        array_push($data_list, $itemData);
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

    /**
     * 批量导入员工
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function import(Request $request, Controller $controller, $saveData , $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.saveSubject');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 批量导入员工--通过文件路径
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $fileName 文件全路径
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function staffImportByFile(Request $request, Controller $controller, $fileName = '', $notLog = 0){
        $company_id = $controller->company_id;
        // $fileName = 'staffs.xlsx';
        $dataStartRow = 1;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
        $headRowNum = 1;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
        $headArr = [
            '记录id'          =>'id',
            '类型'        => 'type_text',
            '分类'        => 'type_name',
            '题目'        => 'title',
            //'答案'        => 'answer_txt',
            '答案'        => 'answer_right',
        ];

        $key = ord("A");
        for($i = 1;$i <= 6 ; $i++){
            $colum = chr($key);
            $headArr['答案' . $colum . '内容'] = $colum;
            $key += 1;
        }
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        try{
            $dataArr = ImportExport::import($fileName, $dataStartRow, $headRowNum, $headArr);
            if(empty($dataArr)) throws('文件没有内容');
            $selTypes = self::$selTypes;
            foreach($dataArr as $k => $v){
                $dataArr[$k]['id'] = $v['id'] ?? '';
                $dataArr[$k]['company_id'] = $company_id;
                $title =  $v['title'] ?? '';
                $type_name = $v['type_name'] ?? '';
                if(empty($type_name)) throws('[' .$title . ']分类不能为空!!');
                $dataArr[$k]['type_id'] = $type_name;
                $type_text = $v['type_text'] ?? '';
                if(! in_array($type_text, $selTypes)) throws('[' . $title . ']类型不正确');
                $subject_type = array_search($type_text,$selTypes);
                if(!is_numeric($subject_type))  throws('[' .$title . ']类型不正确!');
                $dataArr[$k]['subject_type'] = $subject_type;
                $answer_txt = '';
                // $answer_txt = $v['answer_txt'] ?? ''; // 以前的
//                1+1=2||->√
//                2+2=4||->√
//                6+6=10||->×
//                50+6=56||->√
//
//                ×

                $answer_right = $v['answer_right'] ?? ''; // 正确答案
                $answer_right = strtoupper($answer_right);
                $answer_right_arr = explode('、',$answer_right);

                if($subject_type == 4) {// 判断
                    if(trim($answer_right) == '对' || trim($answer_right) == '正确' || trim($answer_right) == '√') {
                        $answer_txt = '√';
                    }else{
                        $answer_txt = '×';
                    }
                }else{// 单选多选
                    $temAnswerArr = [];
                    $key = ord("A");
                    for($j = 1;$j <= 6 ; $j++){
                        $colum = chr($key);
                        $temAnswerVal = $v[$colum];
                        if($temAnswerVal == '' || is_null($temAnswerVal)) break;// 遇到第一个空值，则跳出
                        $answerTag = '×';
                        if(in_array($colum, $answer_right_arr))  $answerTag = '√';
                        array_push($temAnswerArr,$temAnswerVal . self::$answerSplit . $answerTag);
                        $key += 1;
                    }
                    $answer_txt = implode(PHP_EOL ,$temAnswerArr);
                }
                // $dataArr[$k]['answer_txt'] = $answer_txt;

                $answer_list = [];
                $answer = 0;
                if($subject_type == 4){// 判断
                    if(trim($answer_txt) == '对' || trim($answer_txt) == '正确' || trim($answer_txt) == '√') $answer = 1;
                    $dataArr[$k]['answer_list'] = $answer_list;
                    $dataArr[$k]['answer'] = $answer;
                    continue;
                }
                $bigArr = explode(PHP_EOL , $answer_txt);
                $sort_num = count($bigArr);
                $answer_val = 1;

                foreach($bigArr as $b_k => $b_v){
                    if(empty($b_v)) continue;
                    $smallArr = explode(self::$answerSplit , $b_v);
                    $answer_content = $smallArr[0] ?? '';
                    $answer_result = $smallArr[1] ?? '';
                    if(empty($answer_content)) throws('[' .$title . ']答案不能为空');// 答案不能为空
                    $is_right = 1;
                    if(trim($answer_result) == '错' || trim($answer_result) == '错误' || trim($answer_result) == '×') $is_right = 0;
                    $temArr = [
                        'company_id' => $company_id,
                        'answer_content' => $answer_content,
                        'is_right' => $is_right,
                        'answer_val' => $answer_val,
                        'sort_num' => $sort_num--,
                    ];
                    if($is_right == 1) $answer = $answer | $answer_val;
                    array_push($answer_list, $temArr);
                    $answer_val *= 2;
                }
                if(empty($answer_list)) throws('[' .$title . ']不能没有正确答案');
                $dataArr[$k]['answer_list'] = $answer_list;
                $dataArr[$k]['answer'] = $answer;

            }
            if(empty($dataArr)) throws('文件没有正确格式内容');
            $saveData = Tool::formatTwoArrKeys($dataArr, Tool::arrEqualKeyVal(['id', 'company_id', 'type_id', 'subject_type', 'title', 'answer', 'answer_list']), false);

        } catch ( \Exception $e) {
            throws($e->getMessage());
        }
        return self::import($request, $controller, $saveData, $notLog);
    }


}
