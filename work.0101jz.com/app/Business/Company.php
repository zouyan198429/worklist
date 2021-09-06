<?php
// 公司
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class Company extends BaseBusiness
{
    protected static $model_name = 'Company';

    // 开通模块编号【1知识库；2在线考试；4反馈问题；8工单；16我的同事】
    const MODULE_NO_LORE = 1;// 知识库
    const MODULE_NO_EXAM = 2;// 在线考试
    const MODULE_NO_PROBLEM = 4;// 反馈问题
    const MODULE_NO_WORK = 8;// 工单
    const MODULE_NO_STAFF = 16;// 我的同事
    const MODULE_NO_ARR = [
        self::MODULE_NO_LORE => '知识库',
        self::MODULE_NO_EXAM => '在线考试',
        self::MODULE_NO_PROBLEM => '反馈问题',
        self::MODULE_NO_WORK => '工单',
        self::MODULE_NO_STAFF => '我的同事',
    ];

    // 开通状态1开通；2关闭；4作废【过时关闭】；
    const OPEN_STATUS_OPEN = 1;// 开通
    const OPEN_STATUS_CLOSE = 2;// 关闭
    const OPEN_STATUS_CANCEL = 4;// 作废
    const OPEN_STATUS_ARR = [
        self::OPEN_STATUS_OPEN => '开通',
        self::OPEN_STATUS_CLOSE => '关闭',
        self::OPEN_STATUS_CANCEL => '作废',
    ];

    // 性别0未知1男2女
    public static $sexArr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 公司状态;1新注册2试用客户4VIP 8VIP 将过期  16过期会员32过期试用
    public static $companyStatusArr = [
        '1' => '新注册',
        '2' => '试用客户',
        '4' => 'VIP',
        '8' => 'VIP 将过期',
        '16' => '过期会员',
        '32' => '过期试用',
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
//                ['company_id', $company_id],
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
            $company_status = Common::getInt($request, 'company_status');
            if ($company_status > 0) {
                array_push($queryParams['where'], ['company_status', $company_status]);
            }

            $module_no = Common::getInt($request, 'module_no');
            if ($module_no > 0) {
                array_push($queryParams['where'], ['module_no', '&', $module_no . '=' . $module_no]);
            }

            $sex = Common::getInt($request, 'sex');
            if ($sex > 0) {
                array_push($queryParams['where'], ['sex', $sex]);
            }
            $field = Common::get($request, 'field');
            $keyWord = Common::get($request, 'keyWord');

            if (!empty($field) && !empty($keyWord)) {
                array_push($queryParams['where'], [$field, 'like', '%' . $keyWord . '%']);
            }

            // $params = self::formatListParams($request, $controller, $queryParams);
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
         $relations = ['companyDepartment'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => &$v){
            // 接线部门名称
            $data_list[$k]['department_name'] = $v['company_department']['department_name'] ?? '';
            if(isset($data_list[$k]['company_department'])){
                unset($data_list[$k]['company_department']);
            }
            // 加入后台访问地址
            $v['webLoginUrl'] = url($v['id'] . '/login');
            // 加入h5访问地址
            $v['mLoginUrl'] = url(config('public.mWebURL') . 'm/' . $v['id'] . '/login');
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
//            $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//            ImportExport::export('','excel文件名称',$data_list,1, $headArr, 0, ['sheet_title' => 'sheet名称']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
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
        // 前**条[默认]  // 有count下标则是查询数量
        $defaultQueryParams = [
            'where' => [
//                ['company_id', $company_id],
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
            // 'count'=>'0'// 有count下标则是查询数量
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
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }
    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $notLog 是否需要登陆 0需要1不需要 2已经判断权限，不用判断权限
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller, $notLog = 0)
    {
        // return self::delAjaxBase($request, $controller, self::$model_name);
        $model_name = self::$model_name;
        $id = Common::get($request, 'id');
        Tool::dataValid([["input"=>$id,"require"=>"true","validator"=>"","message"=>'参数id值不能为空']]);

        $company_id = $controller->company_id;

        // 判断权限
        if(($notLog & 2) == 2 ) {
            $notLog = $notLog - 2 ;
        }else{
//            $judgeData = [
//                'company_id' => $company_id,
//            ];
//            $relations = '';
//            CommonBusiness::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);
        }

        $queryParams =[// 查询条件参数
            'where' => [
                // ['id', $id],
//                ['company_id', $company_id]
            ]
        ];
        if (strpos($id, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $id]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$id);
        }

        $resultDatas = CommonBusiness::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);

        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $relations = '', $notLog = 0){
        $company_id = $controller->company_id;
        // $relations = '';
        $resultDatas = CommonBusiness::getinfoApi(self::$model_name, $relations, $company_id , $id, $notLog);
        // $resultDatas = self::getInfoDataBase($request, $controller, self::$model_name, $id, $relations);
        // 判断权限
        $judgeData = [
//            'company_id' => $company_id,
        ];
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
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
//                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            // if($modifAddOprate) self::addOprate($request, $controller, $saveData);

        }else {// 新加;要加入的特别字段
            $addNewData = [
//                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            // self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
    }

    /**
     * 登录时，根据企业id，获得企业 信息
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $company_id 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function loginGetInfo(Request $request, Controller $controller, $company_id = 0, $notLog = 0){
        $company_info = [];
        if ($company_id > 0) { // 获得详情数据
            $company_info = Company::getInfoData($request, $controller, $company_id, '', $notLog);
        }
        $module_no = $company_info['module_no'] ?? 0;
        $send_work_department_id = $company_info['send_work_department_id'] ?? 0;
        $open_status = $company_info['open_status'] ?? 0;
        $module_no_text = $company_info['module_no_text'] ?? '';
        $company_name = $company_info['company_name'] ?? '';
        return $company_info;
    }
}
