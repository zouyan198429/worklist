<?php
// 反馈问题
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\HttpRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class CompanyProblem extends BaseBusiness
{
    protected static $model_name = 'CompanyProblem';

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getIndexList(Request $request, Controller $controller, $oprateBit = 2 + 4, $relations = '', $notLog = 0)
    {

        // $relations = ['oprateStaffHistory'];
        $listData = self::getList($request, $controller,$oprateBit, [], $relations , $notLog);
//        $data_list = $listData['result']['data_list'] ?? [];
//        foreach($data_list as $k => $v){
//            // 添加员工名称
//            $data_list[$k]['real_name'] = $v['oprate_staff_history']['real_name'] ?? '';
//            if(isset($data_list[$k]['oprate_staff_history'])) unset($data_list[$k]['oprate_staff_history']);
//        }
//        $listData['result']['data_list'] = $data_list;
        return $listData;

    }
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
//                'id', 'company_id','type_id','type_name','business_id','business_name','customer_id','customer_name'
//                ,'content','call_number','created_at','address','city_id','city_name','area_id','area_name'
//
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        // $params = self::formatListParams($request, $controller, $queryParams);

        $work_type_id = Common::get($request, 'work_type_id');
        $field = Common::get($request, 'field');
        if(empty($field)) $field = "call_number";
        $keyWord = Common::get($request, 'keyWord');

        if(!empty($work_type_id)){
            array_push($queryParams['where'],['work_type_id', $work_type_id]);
        }

        if(!empty($field) && !empty($keyWord)){
            array_push($queryParams['where'],[$field, 'like' , '%' . $keyWord . '%']);
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
        // $relations = ['problemCity', 'problemArea', 'problemCustomerType'];//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            if($isExport == 1) {
                $content = $v['content'] ?? '';
                $data_list[$k]['content'] = replace_enter_char($content, 2);
            }
            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
            // 城市名称
//            $data_list[$k]['city_name'] = $v['problem_city']['area_name'] ?? '';
//            if(isset($data_list[$k]['problem_city'])) unset($data_list[$k]['problem_city']);
            // 区域名称
//            $data_list[$k]['area_name'] = $v['problem_area']['area_name'] ?? '';
//            if(isset($data_list[$k]['problem_area'])) unset($data_list[$k]['problem_area']);
            // 类型名称
//            $data_list[$k]['type_name'] = $v['problem_customer_type']['type_name'] ?? '';
//            if(isset($data_list[$k]['problem_customer_type'])) unset($data_list[$k]['problem_customer_type']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
            $headArr = ['type_name'=>'问题', 'business_name'=>'部门', 'content'=>'内容', 'department_name'=>'发送部门'
                , 'group_name'=>'发送小组', 'operate_staff_name'=>'发送人', 'created_at'=>'时间'];
            ImportExport::export('','反馈问题',$data_list,1, $headArr, 0, ['sheet_title' => '反馈问题']);
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
        $headArr = ['type_name'=>'问题', 'business_name'=>'部门', 'content'=>'内容', 'department_name'=>'发送部门'
                   , 'group_name'=>'发送小组', 'operate_staff_name'=>'发送人', 'created_at'=>'时间'];
        $data_list = [];
        ImportExport::export('','反馈问题导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '反馈问题模版']);
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
                'status' => 0,
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
     * 根据父级id查询 工作类型
     *
     * @param Request $parent_id 父级id
     * @param Request $table 表名
     * @author liuxin
     */
//    public static function getWorkTypeArr(Controller $controller, $parent_id = 0){
//        $company_id = $controller->company_id;
//        // 获得数据
//        $arr = DB::table('company_work_type')
//                ->where([['type_parent_id',  $parent_id], ['company_id', $company_id]])
//                ->get(['id', 'type_name']);
//        return  $arr;
//    }

    /**
     * 根据父级id查询 工作类型
     *
     * @param Request $parent_id 父级id
     * @param Request $table 表名
     * @author liuxin
     */
//    public static function getAreaArr(Controller $controller, $parent_id = 0){
//        $company_id = $controller->company_id;
//        // 获得数据
//        $arr = DB::table('company_area')
//            ->where([['area_parent_id',  $parent_id], ['company_id', $company_id]])
//            ->get(['id', 'area_name']);
//        return  $arr;
//    }


    /*
     *添加问题反馈提交过来的数据
     * */
//    public static function problemAdd(Request $request, Controller $controller){
//        $compay_id = $controller->company_id;
//        $arr = $request->all();
//        $arr['company_id'] = $compay_id;
//        $res = DB::table('company_customer')
//            ->whereRaw('call_number = ? and company_id = ?',[$arr['call_number'], $compay_id])
//            ->select('id','customer_name')
//            ->first();
//        if($res){
//            DB::table('company_customer')->where(['id'=>$res->id])->increment('call_num',1,['updated_at'=>date("Y-m-d H:i:s",time()), 'last_call_date'=>date("Y-m-d H:i:s",time())]);
//            $arr['customer_id']=$res->id;
//            $arr['customer_name']=$res->customer_name;
//            $raw = DB::table('company_problem')->insert($arr);
//            if($raw){
//                return array('status'=>'success','msg'=>'添加成功');
//            }else{
//                return array('status'=>'error','msg'=>'添加失败');
//            }
//        }else{
//            $newarr = [
//              'company_id'=>$compay_id,
//              'call_number'=>$arr['call_number'],
//              'city_id'=>$arr['city_id'],
//              'area_id'=>$arr['area_id'],
//              'address'=>$arr['address'],
//              'call_num'=>1,
//              'operate_staff_id'=>$controller->user_id,
//              'operate_staff_history_id'=>$controller->user_id,
//              'last_call_date'=>date("Y-m-d H:i:s",time()),
//            ];
//            $newId = DB::table('company_customer')->insertGetId($newarr);
//            if($newId){
//                $arr['customer_id']=$newId;
//                $raw = DB::table('company_problem')->insert($arr);
//                if($raw){
//                    return array('status'=>'success','msg'=>'添加成功');
//                }else{
//                    return array('status'=>'error','msg'=>'添加失败');
//                }
//            }else{
//                return array('status'=>'error','msg'=>'参数错误，添加失败');
//            }
//
//        }
//    }


    /**
     * 添加页面初始化要填充的数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 数据
    //        $listData = [
    //            'workFirstList' => $workFirstList,// 1  工单分类第一级
    //            'workCallTypeList' => $page,//  2 工单来电类型
    //            'serviceTagList' => $total,// 4 业务标签
    //            'serviceTimeList' => $aaa,// 8 业务时间
    //            'customerTypeList' => $requestData,// 16 客户类型
    //            'areaCityList' => $requestData,// 32 客户地区
    //            'departmentFirstList' => $requestData,// 64 部门信息
    //            'problemFirstList' => $requestData,// 128反馈分类第一级
    //        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function addInitData(Request $request, Controller $controller)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'operate_no' => 128 + 32 ,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workAddInit');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 添加页面初始化要填充的数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  mixed 数据
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxSave(Request $request, Controller $controller)
    {
        $company_id = $controller->company_id;
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $work_type_id = Common::getInt($request, 'work_type_id');
        $business_id = Common::getInt($request, 'business_id');
//        $call_number = Common::get($request, 'call_number');
        $content = Common::get($request, 'content');
        $content =  replace_enter_char($content,1);
//        $city_id = Common::getInt($request, 'city_id');
//        $area_id = Common::getInt($request, 'area_id');
//        $address = Common::get($request, 'address');

        $saveData = [
            'work_type_id' => $work_type_id, //业务类型
            'business_id' => $business_id,// 业务
            'content' => $content,// 内容
//            'call_number' => $call_number,// 来电号码
//            'city_id' => $city_id,// 区县
//            'area_id' => $area_id,// 街道
//            'address' => $address,// 详细地址
        ];

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

       // $resultDatas = self::replaceById($request, $controller, $saveData, $id);
        // 参数
        $requestData = [
            'id' => $id,
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.saveProblem');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        $result = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
        if($id <= 0){
            $id = $result['id'] ?? 0;
        }
        return $result;
    }

    /**
     * 添加页面初始化要填充的数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  mixed 数据
     * @author zouyan(305463219@qq.com)
     */
    public static function replayAjaxSave(Request $request, Controller $controller)
    {
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $reply_content = Common::get($request, 'reply_content');
        $reply_content =  replace_enter_char($reply_content,1);

        $saveData = [
            'reply_content' => $reply_content,// 内容
            'status' => 1,
        ];

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

         $resultDatas = self::replaceById($request, $controller, $saveData, $id, false);
         return $resultDatas;
        // return ajaxDataArr(1, $resultDatas, '');

    }
}
