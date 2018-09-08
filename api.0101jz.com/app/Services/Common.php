<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 通用工具服务类
 */
class Common
{
    //写一些通用方法
    /*
    public static function test(){
        echo 'aaa';die;
    }
    */

    /**
     * 解析sql条件
     *
     * @param obj &$tbObj
     * @param string $params array || json string
     * @author zouyan(305463219@qq.com)
     */
    public static function resolveSqlParams(&$tbObj ,$params = [])
    {
        if (empty($params) ) {
            return $tbObj;
        }
        if (jsonStrToArr($params , 2, '') === false){
            return $tbObj;
        }
        foreach($params as $key => $param){
            switch($key){
                case 'select':   // 使用一维数组
                    // 查询（Select）--
                    // select('name', 'email as user_email')->get();
                    // ->select(['id','company_id','phonto_name']);
                    if (! empty($param)){
                        $tbObj = $tbObj->select($param);
                    }
                    break;
                case 'addSelect': // 单个字段的字符
                    // 添加一个查询列到已存在的 select 子句，可以使用 addSelect 方法
                    // addSelect('age')->get();
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->addSelect($param);
                    }
                    break;
                case 'distinct': // 空字符
                    // distinct 方法允许你强制查询返回不重复的结果集
                    // $users = DB::table('users')->distinct()->get();
                    $tbObj = $tbObj->distinct();
                case 'where': //使用如下的二维数组.注意，如果是=,第二个参数可以不需要
                    /*[
                            ['status', '=', '1'],
                            ['subscribed', '<>', '1'],
                        ]
                    */
                    // Where 子句
                    // ->where('votes', '=', 100)
                    // ->where('votes', 100)
                    /* ->where([
                        ['status', '=', '1'],
                            ['subscribed', '<>', '1'],
                        ])
                    */
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->where($param);
                    }
                    break;
                case 'orWhere':// orWhere  子句
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->orWhere($param);
                    }
                    break;
                case 'whereDate': // 同where
                    // whereDate 方法用于比较字段值和日期
                    // ->whereDate('created_at', '2016-10-10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereDate($param);
                    }
                    break;
                case 'whereMonth':// 同where
                    // whereMonth 方法用于比较字段值和一年中的指定月份
                    // ->whereMonth('created_at', '10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereMonth($param);
                    }
                    break;
                case 'whereDay':// 同where
                    // whereDay 方法用于比较字段值和一月中的指定日期
                    // ->whereDay('created_at', '10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereDay($param);
                    }
                    break;
                case 'whereYear':// 同where
                    // whereYear 方法用于比较字段值和指定年
                    // ->whereYear('created_at', '2017')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereYear($param);
                    }
                    break;
                case 'whereTime':// 同where
                    // whereTime 方法用于比较字段值和指定时间
                    // ->whereTime('created_at', '=', '11:20')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereTime($param);
                    }
                    break;
                case 'whereBetween':// 数组 [1, 100]
                    // whereBetween  子句
                    // ->whereBetween('votes', [1, 100])
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereBetween($param);
                    }
                    break;
                case 'whereNotBetween':// 数组 [1, 100]
                    // whereNotBetween  子句
                    // ->whereNotBetween('votes', [1, 100])
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereNotBetween($param);
                    }
                    break;
                case 'whereIn': // 数组 [1, 2, 3] 二维数组 [ [字段名=>[多个字段值]],....]
                    // whereIn  子句
                    // ->whereIn('id', [1, 2, 3])
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $field => $vals){
                            $tbObj = $tbObj->whereIn($field,$vals);
                        }
                    }

                    break;
                case 'whereNotIn':// 数组 [1, 2, 3]
                    // whereNotIn  子句
                    // ->whereNotIn('id', [1, 2, 3])
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereNotIn($param);
                    }
                    break;
                case 'whereNull': // 字段字符
                    // whereNull 方法验证给定列的值为 NULL
                    // ->whereNull('updated_at')
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->whereNull($param);
                    }
                    break;
                case 'whereNotNull':// 字段字符
                    // whereNotNull 方法验证给定列的值不是 NULL
                    // ->whereNotNull('updated_at')
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->whereNotNull($param);
                    }
                    break;
                case 'whereColumn':// 同where -二维数组
                    // whereColumn 方法用于验证两个字段是否相等
                    // ->whereColumn('first_name', 'last_name')
                    // 还可以传递一个比较运算符到该方法
                    // ->whereColumn('updated_at', '>', 'created_at')
                    // 还可以传递多条件数组到 whereColumn 方法，这些条件通过 and 操作符进行连接
                    /*
                        ->whereColumn([
                            ['first_name', '=', 'last_name'],
                            ['updated_at', '>', 'created_at']
                        ])
                     */
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereColumn($param);
                    }
                    break;
                case 'orderBy':// 一维数组 ['name'=>'desc','name'=>'desc']
                    // orderBy 的第一个参数应该是你希望排序的字段，第二个参数控制着排序的方向 —— asc 或 desc
                    // ->orderBy('name', 'desc')
                    // ->orderBy('name', 'desc')
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $orderField => $orderType){
                            $tbObj = $tbObj->orderBy($orderField,$orderType);
                        }

                    }
                    break;
                case 'latest':
                    // latest 和 oldest 方法允许你通过日期对结果进行排序，默认情况下，结果集根据 created_at 字段进行排序，或者，你可以按照你想要排序的字段作为字段名传入
                    // ->latest()
                    $tbObj = $tbObj->latest();
                    break;
                case 'oldest'://
                    $tbObj = $tbObj->oldest();
                    break;
                case 'inRandomOrder':// inRandomOrder 方法可用于对查询结果集进行随机排序，比如，你可以用该方法获取一个随机用户
                    $tbObj = $tbObj->inRandomOrder();
                    break;
                case 'groupBy':// 字段字符 或 一维数组 ['字段一','字段二']
                    // groupBy / having-对结果集进行分组
                    /*
                    ->groupBy('account_id')
                    ->having('account_id', '>', 100)
                    */
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->groupBy($param);
                    }else if(is_array($param)){
                        foreach($param as $groupField ){
                            $tbObj = $tbObj->groupBy($groupField);
                        }
                    }
                    break;
                case 'having':// 一维数组 [$havingField,$havingOperator,$havingValue]
                    if ( (! empty($param)) && is_array($param)){
                        $havingField = $param[0] ?? '';
                        $havingOperator = $param[1] ?? '';
                        $havingValue = $param[2] ?? '';
                        $tbObj = $tbObj->having($havingField, $havingOperator,$havingValue);
                    }
                    break;
                case 'skip': // 数字
                    // skip / take-限定查询返回的结果集的数目
                    // ->skip(10)->take(5)
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->skip($param);
                    }
                    break;
                case 'take':// 数字
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->take($param);
                    }
                    break;
                case 'limit':// 数字
                    // 为替代方法，还可以使用 limit 和 offset 方法
                    /*  ->offset(10)
                        ->limit(5)
                    */
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->limit($param);
                    }
                    break;
                case 'offset':// 数字
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->offset($param);
                    }
                    break;
                case 'find':// 单个数字 或 数组
                    // find 和 first 获取单个记录
                    // App\Flight::find(1);
                    // App\Flight::find([1, 2, 3]);
                    if ( (! empty($param))){
                        $tbObj = $tbObj->find($param);
                    }
                    break;
                case 'first':
                    // ->first();
                    $tbObj = $tbObj->first();
                    break;
                case 'findOrFail':// 单个数字 或 数组
                    // findOrFail 和 firstOrFail方法会获取查询到的第一个结果。不过，如果没有任何查询结果，Illuminate\Database\Eloquent\ModelNotFoundException 异常将会被抛出
                    if ( (! empty($param))){
                        $tbObj = $tbObj->findOrFail($param);
                    }
                    break;
                case 'firstOrFail':// bbb  子句
                    $tbObj = $tbObj->firstOrFail();
                    break;
                case 'value': // 字段名
                    // 不需要完整的一行，可以使用 value 方法从结果中获取单个值，该方法会直接返回指定列的值
                    // ->value('email');
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->value($param);
                    }
                    break;
                case 'pluck':// 字符 '字段名'或 ['字段名'] 或  ['别名'=>'字段名']
                    // 获取包含单个列值的数组，可以使用 pluck 方法
                    /*
                    $titles = DB::table('roles')->pluck('title');

                    foreach ($titles as $title) {
                        echo $title;
                    }

                    列值指定自定义键
                    ->pluck('title', 'name');

                    */
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $k => $v){
                            if(is_string($k)){
                                $tbObj = $tbObj->pluck($v,$k);
                            }else{
                                $tbObj = $tbObj->pluck($v);
                            }
                        }
                    }else if( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->pluck($param);
                    }

                    break;
                case 'count':// 获取聚合结果-  count, max, min, avg 和 sum
                    // ->count();
                    $tbObj = $tbObj->count();
                    break;
                case 'max':// 字段名
                    // ->max('price')
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->max($param);
                    }
                    break;
                case 'avg':// 字段名
                    // ->avg('price');
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->avg($param);
                    }
                    break;
                case 'sum':// bbb  子句
                    $tbObj = $tbObj->sum();
                    break;
                case 'exists':// 判断记录是否存在- exists 或 doesntExist 方法
                    // return DB::table('orders')->where('finalized', 1)->exists();
                    $tbObj = $tbObj->exists();
                    break;
                case 'doesntExist':// bbb  子句
                    // return DB::table('orders')->where('finalized', 1)->doesntExist();
                    $tbObj = $tbObj->doesntExist();
                    break;
                default:
            }

        }
        return $tbObj;
    }

    /**
     * 解析表关系
     *
     * @param obj &$tbObj
     * @param string $relations array || json string
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function resolveRelations(&$tbObj ,$relations = [])
    {
        if (empty($relations)) {
            return $tbObj;
        }

        if (jsonStrToArr($relations , 2, '') === false){
            return $tbObj;
        }

        // 层关系
        $tbObj->load($relations);
        return $tbObj;
    }

    /**
     * 获得model所有记录
     *
     * @param object $modelObj 当前模型对象
     * @param json/array $queryParams 查询条件
     * @param json/array $relations 要查询的与其它表的关系
     * @return object 数据对象
     * @author zouyan(305463219@qq.com)
     */
    public static function getAllModelDatas(&$modelObj,$queryParams,$relations){
        /*
        $queryParams = [
            'where' => [
                // ['id', '1'],
                // ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);

        if (true) {// 在处理大量数据集合时能够有效减少内存消耗
            $requestData = collect([]);
            $modelObj->chunk(500, function ($flights) use (&$requestData, $relations) {
                self::resolveRelations($flights, $relations);
                // $flights->load('siteResources');

                $requestData= $requestData->concat($flights);
                /*
                  foreach ($flights as $flight) {
                      //
                  }
                */
            });
        } else {
            $requestData = $modelObj->get();
            // 查询关系参数
            self::resolveRelations($requestData, $relations);
            // $requestData->load($relations);

            //return $infos;
        }
        return $requestData;
    }

    public static function getModelListDatas(&$modelObj,  $page = 1, $pagesize = 10, $total = 0, $queryParams = [], $relations = [] ){
        // 偏移量
        $offset = ($page-1) * $pagesize;

        $limitParams = [
            'limit' => $pagesize,
            // 'take' => $pagesize,
            'offset' => $offset,
            // 'skip' => $offset,
        ];
        /*
        $queryParams = [
            'where' => [
                ['id', '>', '0'],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
            // 'limit' => $pagesize,
            // 'take' => $pagesize,
            // 'offset' => $offset,
            // 'skip' => $offset,
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        if ($total <= 0){ // 需要获得总页数
            if (isset($queryParams['limit'])) unset($queryParams['limit']);
            if (isset($queryParams['offset'])) unset($queryParams['offset']);
            if (isset($queryParams['take'])) unset($queryParams['take']);
            if (isset($queryParams['skip'])) unset($queryParams['skip']);
            if (isset($queryParams['orderBy'])) {
                $limitParams['orderBy'] = $queryParams['orderBy'];
                unset($queryParams['orderBy']);
            }

            // 获得总数量
            self::resolveSqlParams($modelObj, $queryParams);
            $total = $modelObj->count();
        } else {
            $limitParams = array_merge($queryParams,$limitParams);
        }

        // 获得数据
        self::resolveSqlParams($modelObj, $limitParams);
        $requestData = $modelObj->get();

        // 获得关联系关系
        self::resolveRelations($requestData, $relations);

        $listData = [
            'pageSize' => $pagesize,
            'page' => $page,
            'total' => $total,
            'totalPage' => ceil($total/$pagesize),
            'dataList' => $requestData,
        ];
        return $listData;
    }


    /**
     * 获得所有列表接口
     * 注意参数是request来的参数
     * @param string 必填 $Model_name model名称
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function requestAllDataByModel(Request $request)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);

        // 查询条件参数 及 查询关系参数
        list($queryParams,$relations) = array_values(self::getQueryParams($request, $modelObj));


        $requestData = self::getAllModelDatas($modelObj, $queryParams, $relations);

        return okArray($requestData);
    }

    /**
     * 获得列表接口
     * 注意参数是request来的参数
     * @param string 必填 $Model_name model名称 或传入 $modelObj 对象
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @param int 选填 $page 当前页page [默认1]
     * @param int 选填 $pagesize 每页显示的数量 [默认15]
     * @param int 选填 $total 总记录数,优化方案：传<=0传重新获取总数[默认0]
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function requestListDataByModel(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);

        // 获得翻页的三个关键参数
        list($page, $pagesize, $total) = array_values(self::getPageParams($request));

        // 查询条件参数 及 查询关系参数
        list($queryParams,$relations) = array_values(self::getQueryParams($request, $modelObj) );

        $requestData = self::getModelListDatas($modelObj, $page, $pagesize, $total, $queryParams, $relations);

        return okArray($requestData);
    }

    public static function requestInfoByID(Request $request, &$modelObj = null){

        $id = self::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 获得对象
        self:: requestGetObj($request,$modelObj);

        $requestData = $modelObj->find($id);
        // 查询关系参数
        $relations = self::get($request, 'relations');
        // 查询关系参数
        self::resolveRelations($requestData, $relations);
        return  okArray($requestData);
    }



    public static function requestDel(Request $request, &$modelObj = null){
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 条件数组
        $queryParams = self::get($request, 'queryParams');
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // $requestData =$modelObj->where($queryParams)->delete();

        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);
        $requestData = $modelObj->delete();
        return okArray($requestData);
    }

    public static function requestSync(Request $request){
        $id = self::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 获得对象
        self:: requestGetObj($request,$modelObj);

        $requestData = $modelObj->find($id);
        // 查询关系同步参数
        $synces = self::get($request, 'synces');
        // json 转成数组
        jsonStrToArr($synces , 1, '参数[synces]格式有误!');

        // 同步修改关系 TODO ；以后改为事务
        DB::beginTransaction();
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        foreach($synces as $rel => $relIds){
            try {
               $requestData->{$rel}()->sync($relIds);
               array_push($successRels['success'],$relIds);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[ 'ids'=> $relIds,'msg'=>$e->getMessage() ]);
                throws('同步关系[' . $rel . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());

            }
        }
        DB::commit();
        return  okArray($successRels);
    }

    public static function requestDetach(Request $request){
        $id = self::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 获得对象
        self:: requestGetObj($request,$modelObj);

        $requestData = $modelObj->find($id);
        // 查询关系同步参数
        $detaches = self::get($request, 'detaches');
        // json 转成数组
        jsonStrToArr($detaches , 1, '参数[detaches]格式有误!');

        // 同步修改关系 TODO ；以后改为事务
        DB::beginTransaction();
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        foreach($detaches as $rel => $relIds){
            try {
                if(empty($relIds)){
                    $requestData->{$rel}()->detach();
                }else{
                    $requestData->{$rel}()->detach($relIds);
                }
                array_push($successRels['success'],$rel);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[$rel =>$e->getMessage() ]);
                throws('移除关系[' . $rel . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());

            }
        }
        DB::commit();
        return  okArray($successRels);
    }

    //新加
    public static function requestCreate(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 字段数组
        $dataParams = self::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');
        $requestData =$modelObj->create($dataParams);
        return okArray($requestData);
    }

    //批量新加-data只能返回成功true:失败:false
    public static function requestCreateBath(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 字段数组
        $dataParams = self::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');
        $requestData =$modelObj->insert($dataParams);//一维或二维数组;只返回true:成功;false：失败
        // $requestData =$modelObj->insertGetId($dataParams,'id');//只能是一维数组，返回id值
        return okArray($requestData);
    }

    //批量新加-data只能返回成功true:失败:false
    public static function requestCreateBathByPrimaryKey(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 字段数组
        $dataParams = self::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $primaryKey = Common::get($request, 'primaryKey');
        if(empty($primaryKey)){
            $primaryKey = 'id';
        }
        $newIds = [];
        DB::beginTransaction();
        foreach($dataParams as $info){
            // 保存记录
            try {
                $newId =$modelObj->insertGetId($info,$primaryKey);//只能是一维数组，返回id值
                array_push($newIds,$newId);
            } catch ( \Exception $e) {
                DB::rollBack();
                throws('保存失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return okArray($newIds);
    }

    // 通过id修改记录
    public static function requestSave(Request $request, &$modelObj = null){
        $id = self::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 获得对象
        self:: requestGetObj($request,$modelObj);

        $requestData = $modelObj->find($id);

        // 字段数组
        $dataParams = self::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        foreach($dataParams as $field => $val){
            $requestData->$field = $val;
        }
        $result = $requestData->save();
        return okArray($result);
    }

    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function batchSaveByPrimaryKey(Request $request)
    {
        // 获得对象
        $modelName = self::get($request, 'Model_name');
        self::judgeEmptyParams($request, 'Model_name', $modelName);

        $className = "App\\Models\\" .$modelName;
        if (! class_exists($className )) {
            throws('参数[Model_name]不正确！');
        }

        $dataParams = Common::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $primaryKey = Common::get($request, 'primaryKey');
        if(empty($primaryKey)){
            $primaryKey = 'id';
        }
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        DB::beginTransaction();
        foreach($dataParams as $info){
            // 保存记录
            $id = $info[$primaryKey] ?? '';
            try {
                $temObj = $className::find($id);
                unset($info[$primaryKey]);
                if(empty($info)){
                    continue;
                }
                foreach($info as $field => $val){
                    $temObj->{$field} = $val;
                }
                $res = $temObj->save();
                array_push($successRels['success'],[$id => $res]);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[ 'id'=> $id,'msg'=>$e->getMessage() ]);
                throws('修改[' . $id . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return  okArray($successRels);
    }

    // 按条件修改
    public static function requestUpdate(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);

        // 条件数组
        $queryParams = self::get($request, 'queryParams');
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 字段数组
        $dataParams = self::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        // $requestData =$modelObj->where($queryParams)->update($dataParams);

        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);
        $requestData = $modelObj->update($dataParams);
        return okArray($requestData);
    }

    //自增自减,通过条件-data操作的行数
    public static function requestSaveDecIncByQuery(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 条件数组
        $queryParams = self::get($request, 'queryParams');
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');
        // 增减类型 inc 增 ;dec 减[默认]
        $incDecType = self::get($request, 'incDecType');

        // 增减字段
        $incDecField = self::get($request, 'incDecField');
        self::judgeEmptyParams($request, 'incDecField', $incDecField);
        // 增减值
        $incDecVal = self::get($request, 'incDecVal');
        if(!is_numeric($incDecVal)){
            throws('参数[incDecVal]必须是数字!');
        }
        // 修改的其它字段 -没有，则传空数组json
        $modifFields = self::get($request, 'modifFields');
        jsonStrToArr($modifFields , 1, '参数[modifFields]格式有误!');

        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);

        $operate = 'decrement'; // 减
        if($incDecType == 'inc'){
            $operate = 'increment';// 增
        }
        if(is_array($modifFields) && (!empty($modifFields))){
            $requestData = $modelObj->{$operate}($incDecField, $incDecVal,$modifFields);
        }else{
            $requestData = $modelObj->{$operate}($incDecField, $incDecVal);
        }
        return okArray($requestData);

    }

    /**
     * 批量修改设置
     *
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符 ;
     *
        $dataParams = [
            [
                'Model_name' => 'model名称',
                'primaryVal' => '主键字段值',
                'incDecType' => '增减类型 inc 增 ;dec 减[默认]',
                'incDecField' => '增减字段',
                'incDecVal' => '增减值',
                'modifFields' => '修改的其它字段 -没有，则传空数组',
            ],
        ];
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function batchSaveDecIncByPrimaryKey(Request $request)
    {
        $dataParams = Common::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $successRels = [];
        DB::beginTransaction();
        foreach($dataParams as $info){
            try {
                $primaryVal = $info['primaryVal'] ?? '';//Common::get($request, 'primaryKey');
                if(empty($primaryVal)){
                    throws('参数[primaryVal]不能为空！');
                }
                // 获得对象
                $modelName = $info['Model_name'] ?? '';//self::get($request, 'Model_name');
                self::judgeEmptyParams($request, 'Model_name', $modelName);

                $className = "App\\Models\\" .$modelName;
                if (! class_exists($className )) {
                    throws('参数[Model_name]不正确！');
                }

                // 增减类型 inc 增 ;dec 减[默认]
                $incDecType = $info['incDecType'] ?? 'dec';//self::get($request, 'incDecType');

                // 增减字段
                $incDecField = $info['incDecField'] ?? '';//self::get($request, 'incDecField');
                self::judgeEmptyParams($request, 'incDecField', $incDecField);
                // 增减值
                $incDecVal = $info['incDecVal'] ?? '';//self::get($request, 'incDecVal');
                if(!is_numeric($incDecVal)){
                    throws('参数[incDecVal]必须是数字!');
                }
                // 修改的其它字段 -没有，则传空数组json
                $modifFields = $info['modifFields'] ?? [];//self::get($request, 'modifFields');
                // jsonStrToArr($modifFields , 1, '参数[modifFields]格式有误!');


                // 保存记录
                $operate = 'decrement'; // 减
                if($incDecType == 'inc'){
                    $operate = 'increment';// 增
                }
                $temObj = $className::find($primaryVal);
                if(is_array($modifFields) && (!empty($modifFields))){
                    $res = $temObj->{$operate}($incDecField, $incDecVal,$modifFields);
                }else{
                    $res = $temObj->{$operate}($incDecField, $incDecVal);
                }
                array_push($successRels,$res);
            } catch ( \Exception $e) {
                DB::rollBack();
                throws('保存[' . $primaryVal . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return  okArray($successRels);
    }

    //自增自减-data操作的行数
    public static function requestSaveDecIncqqqqq(Request $request, &$modelObj = null)
    {
        // 获得对象
        self:: requestGetObj($request,$modelObj);
        // 字段数组
        $dataParams = self::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');
        $requestData =$modelObj->find(7)->increment('validate_num', 5);
        return okArray($requestData);
    }

    public static function requestGetObj(Request $request,&$modelObj = null){
        if (! is_object($modelObj)) {
            $modelName = self::get($request, 'Model_name');
            self::judgeEmptyParams($request, 'Model_name', $modelName);

            $className = "App\\Models\\" .$modelName;
            if (! class_exists($className )) {
                throws('参数[Model_name]不正确！');
            }
            $modelObj = new $className();

        }
        return $modelObj;

    }

    // 先从get获取，没有再从post获取
    public static function get(Request $request, $key)
    {
        $value  = $request->get($key) ?: $request->post($key);
        // $value = StringHelper::deepFilterDatas($value, ['trim', 'strip_tags']);
        if(is_null($value)){ $value = '';}
        return $value;
    }

    public static function getInt(Request $request, $key)
    {
        return (int) self::get($request, $key);
    }

    public static function getInts(Request $request, $key)
    {
        $value = self::get($request,$key);

        return is_array($value) ? array_filter(array_map('intval', $value)) : intval($value);
    }

    public static function getBool(Request $request, $key)
    {
        return (bool) self::get($request, $key);
    }

    public static function getPosts(Request $request)
    {
        $params = $request->post() ?? [];

        // 兼容 RAW-JSON
//        if (Yii::$app->request->headers->get('content-type') == 'application/json') {
//            $params = array_merge(
//                $params,
//                json_decode(Yii::$app->request->getRawBody(), true) ?? []
//            );
//        }

        // $params = StringHelper::deepFilterDatas($params, ['trim', 'strip_tags']);

        return $params;
    }

//    public static function outputJson(Request $request, $resp)
//    {
//        header('Content-type: text/json');
//        header('Content-type: application/json; charset=UTF-8');

//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Max-Age: 864000');
//
//        // 允许所有自定义请求头
//        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
//            header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
//        }
//        return response()->json($resp);
        // 创建一个 JSONP 响应
//        return response()
//            ->json($resp)
//            ->withCallback($request->input('callback'));

//    }

    // 判断参数
    public static function judgeInitParams(Request $request, $paramName, $pramVal)
    {
        if (((int )$pramVal) <=0){
            throws('参数[' . $paramName . ']必须为整数！');
        }
    }

    // 判断是否为空
    public static function judgeEmptyParams(Request $request, $paramName, $pramVal)
    {
        if (empty($pramVal)){
            throws('参数[' . $paramName . ']不能为空！');
        }
    }

    // 获得翻页的三个关键参数
    public static function getPageParams(Request $request){

        // 当前页page,如果不正确默认第一页
        $page = self::getInt($request, 'page');
        if ( (! is_numeric($page)) || $page<=0 ){ $page = 1; }

        // 每页显示的数量,取值1 -- 100 条之间,默认20条
        $pagesize = self::getInt($request, 'pagesize');
        //if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 100 ){ $pagesize = 15; }
        if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 10000 ){ $pagesize = 15; }

        // 总记录数,优化方案：传0传重新获取总数，如果传了，则不会再获取，而是用传的，减软数据库压力
        $total = self::getInt($request, 'total');
        if ( (! is_numeric($total)) || $total<0 ){ $total = 0; }
        return [
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,
        ];
    }

    /**
     * 查询参数处理
     *
     * @return Response
     * @param obj $modelObj 数据模型对象
     * @return  string :错误信息 array:查询参数及查询关系参数
     * @author zouyan(305463219@qq.com)
     */
    public static function getQueryParams(Request $request, &$modelObj){
        // 查询条件参数
        $queryParams = self::get($request, 'queryParams');
        if(empty($queryParams)) $queryParams = [];
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 查询关系参数
        $relations = self::get($request, 'relations');
        if(empty($relations)) $relations = [];
        // json 转成数组
        jsonStrToArr($relations , 1, '参数[relations]格式有误!');

        /*
        $queryParams = [
            'where' => [
                ['id', '>', '0'],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
            // 'limit' => $pagesize,
            // 'take' => $pagesize,
            // 'offset' => $offset,
            // 'skip' => $offset,
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        return [
            'queryParams' => $queryParams,
            'relations' => $relations,
        ];
    }

    // 后缀可区分环境
    public static function getSnSuffix()
    {
        static $suffixes = [
            'dev'  => 1,
            'test' => 2,
            'prod' => 0,
        ];

        $suffix = $suffixes[YII_ENV] ?? 9;

        return $suffix;
    }

}
