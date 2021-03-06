<?php
// 资源表
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class Resource extends BaseBusiness
{
    protected static $model_name = 'Resource';
    // 大后台 admin/年/月/日/文件
    // 企业 company/[生产单元/]年/月/日/文件
    protected static $source_path = '/resource/company/';
    protected static $source_tmp_path = '/resource/tmp/';// 临时文件夹
    protected static $cache_block = 2; // 1 redis缓存分片内容--适合redis内存比较大的服务器，2 临时文件缓存分片内容--redis内存比较小时

    public static $resource_type = [
            '0' => [
                'name' => '图片文件',
                'ext' => ['jpg','jpeg','gif','png','bmp','ico'],// 扩展名
                'dir' => 'images',// 文件夹名称
                'maxSize' => 5,// 文件最大值  单位 M
                'other' => [],// 其它各自类型需要判断的指标
            ],
            '1' => [
                'name' => 'excel文件',
                'ext' => ['xlsx', 'xls'],// 扩展名
                'dir' => 'excel',// 文件夹名称
                'maxSize' => 10,// 文件最大值 单位 M
                'other' => [],// 其它各自类型需要判断的指标
            ]
    ];

    /**
     * 获得列表数据--根据图片ids
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $company_id 企业id
     * @param string $ids  查询的id ,多个用逗号分隔,
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getResourceByIds(Request $request, Controller $controller, $company_id = 0, $ids = '', $notLog = 0){
        if(empty($ids)) return [];
        $queryParams = [
            'where' => [
            //    ['company_id', $company_id],
                // ['operate_staff_id', $user_id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
             'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(is_numeric($company_id) && $company_id > 0) array_push($queryParams['where'],['company_id', $company_id]);

        if (!empty($ids)) {
            if (strpos($ids, ',') === false) { // 单条
                array_push($queryParams['where'], ['id', $ids]);
            } else {
                $queryParams['whereIn']['id'] = explode(',', $ids);
            }
        }
        $result = self::getList($request, $controller, 1 + 0, $queryParams, [], ['useQueryParams' => false], $notLog);
        $data_list = $result['result']['data_list'] ?? [];
        $reList = [];
        foreach($data_list as $k => $v){
            $temArr = [
                'id' => $v['id'],
                'resource_name' => $v['resource_name'],
                'resource_url' => url($v['resource_url']),
                'created_at' => $v['created_at'],
            ];
            array_push($reList, $temArr);
        }
        return $reList;
    }

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
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['data_list'] = $data_list;
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
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }

    /**
     * 上传文件
     * post参数 photo 文件；name  文件名称;note 资源说明[可为空];;;;
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $resource_type 资源类型
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function uploadFile(Request $request, Controller $controller, $resource_type = 0)
    {
//            $controller->company_id = 1;
//            $controller->operate_staff_id = 1502;

        try{
            $company_id = $controller->company_id;
            $operate_staff_id =  $controller->operate_staff_id;
            if(!is_numeric($operate_staff_id)) $operate_staff_id = 0;

            ini_set('memory_limit','1024M');    // 临时设置最大内存占用为 3072M 3G
            ini_set("max_execution_time", "300");
            set_time_limit(300);   // 设置脚本最大执行时间 为0 永不过期

            // $pro_unit_id = Common::getInt($request, 'pro_unit_id');
            $name = Common::get($request, 'name'); // 文件名称 "测试名称"
            Log::info('上传文件日志-name',[$name]);//
            $resource_note = Common::get($request, 'note'); // 资源说明
            // 日志
            $requestLog = [
                'files'       => $request->file(),
                'posts'  => $request->post(),
                'input'      => $request->input(),
            ];
            Log::info('上传文件日志',$requestLog);

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $uuid =  Common::get($request, 'uuid');
                Log::info('上传文件日志-uuid',[$uuid]);// o_1d1r9ofhi5v88bk46di171f53a
                $num = Common::getInt($request, 'chunk');// 分片序号 0,1,2  或 0
                Log::info('上传文件日志-分片序号',[$num]);// 分片序号 [0]
                $count = Common::getInt($request, 'chunks');// 分片总数量 3 或 1
                Log::info('上传文件日志-分片总数量count',[$count]);// 分片总数量count [1]
                Log::info('上传文件日志-文件信息',[$photo]);// 文件信息 ["[object] (Illuminate\\Http\\UploadedFile: /tmp/phpa2eXco)"]
                //获取原文件名
                $originalName = $photo->getClientOriginalName();
                Log::info('上传文件日志-原文件名',[$originalName]);// 原文件名 ["7.jpg"] 原文件名 ["blob"]
                // 文件类型
                $type = $photo->getClientMimeType();
                Log::info('上传文件日志-文件类型',[$type]);// 文件类型 ["image/jpeg"]
                //临时绝对路径
                $realPath = $photo->getRealPath();
                Log::info('上传文件日志-临时绝对路径',[$realPath]);// 临时绝对路径 ["/tmp/phpa2eXco"]
                // 扩展名 jpg
                $extFirst = strtolower($photo->extension());// 扩展名  该扩展名可能会和客户端提供的扩展名不一致
                $ext = $photo->getClientOriginalExtension(); //上传文件的后缀. 扩展名
                Log::info('上传文件日志-文件后缀extFirst',[$extFirst]);// 文件后缀extFirst ["jpeg"]
                Log::info('上传文件日志-文件后缀',[$ext]);// 文件后缀 ["jpg"]

                if(empty($ext)) $ext = $extFirst;
                $hashname = $photo->hashName();// "geEGcIfovpc8gRSlTYREDZiW4frld0helrZKzmoA.jpeg"
                //获取上传文件的大小
                $size = $photo->getSize();
                Log::info('上传文件日志-文件大小',[$size]);// 文件大小 [61563]
                // 分片时，所有片的共同标识
                $allBlockUuid = $operate_staff_id . $originalName . $uuid . $count;
                Log::info('上传文件日志-分片时，所有片的共同标识',[$allBlockUuid]);//

                $temFile = [
                    'extension' => $ext,
                    'hashname' => $hashname,
                    'name' => $name,
                ];
                // 文件信息 {"extension":"jpg","hashname":"vggBtITcoKWwYCcRhheEIBsEr6upX16WnYTadvFY.jpeg","name":"7.jpg"}
                Log::info('上传文件日志-文件信息',$temFile);
                $fileArr =[
                    'name' => $name,// 文件名称
                    'allBlockUuid' => $allBlockUuid,// 分片时，所有片的共同标识
                    'ext' => $ext,// 文件扩展名
                    'size' => $size,// 文件大小
                    'count' => $count,// 分片总数量 3
                    'saveData' => [// 要保存的一维数据
                        'resource_note' => $resource_note,
                    ],
                ];
                //直接保存
                if( ($num == $count &&  $count <= 1) || ($num ==0 && $count == 1)){
                    return self::saveFile($request, $controller, 1, $company_id, $resource_type, $photo, $fileArr);
                }else{
                    //分片临时文件名
                    $filename = md5($allBlockUuid).'-'.($num + 1).'.tmp';
                    //上传目录
                    $path_name = self::$source_tmp_path . $filename;// 'uploads/tmp/'.$filename;
                    if(self::$cache_block == 2){
                        //方式一、保存临时文件
                        // $bool = Storage::disk('tmp')->put($filename, file_get_contents($realPath));
                         $store_result = $photo->storeAs(self::$source_tmp_path, $filename);// 保存片文件
                    }else{
                        // 方式二、将内容写入缓存
                        $publicPath = Tool::getPath('public');
                        //打开临时文件
                        // $handle = fopen($publicPath . $path_name,"rb");
                        $handle = fopen($realPath,"rb");
                        //读取临时文件 写入最终文件
                        Tool::setRedis('tmpFilesBin', md5($path_name), fread($handle, filesize($realPath)), 60*5, 2); // 5分钟
                        //关闭句柄 不关闭删除文件会出现没有权限
                        fclose($handle);
                    }
                    // 缓存0的扩展名
                    if ($num == 0){
                        Tool::setRedis('extend', md5($allBlockUuid), $ext, 60*5, 2); // 5分钟
                    }
                    // 文件大小处理
                    $allSize = Tool::getRedis('size' . md5($allBlockUuid), 2);
                    if(!is_numeric($allSize)) $allSize = 0;
                    Tool::setRedis('size', md5($allBlockUuid), $allSize + $size, 60*5, 2); // 5分钟

                    // 缓存分存临时文件名
                    $tmpFiles = Tool::getRedis('tmpFiles' . md5($allBlockUuid), 2);
                    if(!is_array($tmpFiles)) $tmpFiles = [];
                    array_push($tmpFiles, $path_name);

                    Tool::setRedis('tmpFiles', md5($allBlockUuid), $tmpFiles, 60*5, 2); // 5分钟

                    //当分片上传完时 合并
                    // if(($num + 1) == $count){
                    if( count($tmpFiles) >= $count){// 因为可能是无序的，所以只能通过总数量来判断
                        // 扩展名和文件大小处理
                        $fileArr = array_merge($fileArr, [
                            'ext' => Tool::getRedis('extend' . md5($allBlockUuid), 2),// 文件扩展名
                            'size' => Tool::getRedis('size' . md5($allBlockUuid), 2),// 文件大小
                        ]);
                        return self::saveFile($request, $controller, 2, $company_id, $resource_type, $photo, $fileArr);
                    }else{
                        return [
                            'id' => 0,// 资源id
                            'name' => $name,// 文件名
                            'savPath' => $path_name,// 保存路径 /结束
                            'saveName' => $filename,// 保存文件名称
                            'store_result' => $path_name,// storeAs
                            'info' => [],// 资源表记录 一维
                        ];
                    }
                }
            }else{
                throws('请选择要上传的文件！');
            }
        } catch ( \Exception $e) {
            if(isset($allBlockUuid)){
                // 缓存分存临时文件名
                $tmpFiles = Tool::getRedis('tmpFiles' . md5($allBlockUuid), 2);
                if(!is_array($tmpFiles)) $tmpFiles = [];
                if(!empty($tmpFiles)) Log::info('上传文件日志-分片失败，删除临时文件',[$tmpFiles]);
                foreach($tmpFiles as $tmp_files){
                    if(self::$cache_block == 2){
                        // 方式一、删除临时文件
                         @unlink($tmp_files);
                    }else{
                        // 方式二、删除缓存
                        Tool::setRedis('tmpFilesBin', md5($tmp_files), '', 2, 2); // 2 秒
                    }
                }
                // 清除缓存
                // 缓存0的扩展名
                Tool::setRedis('extend', md5($allBlockUuid), '', 2, 2); // 2秒
                // 文件大小处理
                Tool::setRedis('size', md5($allBlockUuid), 0, 2, 2); // 2秒
                // 缓存分存临时文件名
                Tool::setRedis('tmpFiles', md5($allBlockUuid), [], 2, 2); // 2秒

            }
            throws($e->getMessage());
        }
    }

    /**
     * 保存文件
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $operate_type 操作类型 1 单文件保存 2 分片文件保存
     * @param int $company_id 企业id
     * @param int $resource_type 资源类型
     * @param object $photo 上传文件对象
     * @param array $fileArr 文件信息
         $fileArr =[
            'name' => '',// 文件名称
            'allBlockUuid' => '',// 分片时，所有片的共同标识
            'ext' => 'jpg',// 文件扩展名
            'size' => '',// 文件大小
            'count' => '',// 分片总数量 3
            'saveData' => [// 要保存的一维数据
                // 'resource_note' => $resource_note,
            ],
        ];
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function saveFile(Request $request, Controller $controller, $operate_type = 1, $company_id, $resource_type, &$photo = null, $fileArr = []){

        $name = $fileArr['name'] ?? '';
        $allBlockUuid = $fileArr['allBlockUuid'] ?? '';
        $ext = $fileArr['ext'] ?? '';
        $size =  $fileArr['size'] ?? 0;
        $count =  $fileArr['count'] ?? 0;
        $saveData =  $fileArr['saveData'] ?? [];
        // 生成保存路径
        $savPath = self::$source_path . $company_id . '/';

        $resourceTypeArr = self::$resource_type[$resource_type] ?? [];
        if(empty($resourceTypeArr)) throws('不明确的资源类型!');
        $typeName = $resourceTypeArr['name'] ?? '';// 类型名称
        $typeExt = $resourceTypeArr['ext'] ?? [];// 扩展名
        $typeDir = $resourceTypeArr['dir'] ?? '';// 文件夹名称
        $typeMaxSize = $resourceTypeArr['maxSize'] ?? '0.5';// 文件最大值 单位 M
        if(!is_numeric($typeMaxSize)) $typeMaxSize = 0.5;// 0.5M
        $typeOther = $resourceTypeArr['other'] ?? [];// 其它各自类型需要判断的指标
        if(!in_array($ext , $typeExt)) throws($typeName . '扩展名必须为[' . implode('、', $typeExt) . ']');

        //这里可根据配置文件的设置，做得更灵活一点
        if($size > $typeMaxSize * 1024 * 1024){
            throws('上传文件不能超过[' . $typeMaxSize . 'M]');
        }

        if($typeDir != '' ) $savPath .=   $typeDir . '/';// 类型文件夹

        //if(is_numeric($pro_unit_id)){
        //    $savPath .=   'pro' . $pro_unit_id . '/';
        //}

        $savPath .= date('Y/m/d/',time());

        $saveName = Tool::createUniqueNumber(30) .'.' . $ext;
        //$store_result = $photo->store('photo');
        try{
            if($operate_type == 1 ){// 小于等于0时，为没有分片上传 !empty($photo)
                $store_result = $photo->storeAs($savPath, $saveName);// 返回 "resource/company/1/pro0/2018/10/13//20181013182843dc1a9783e212840f.jpeg"
            }else{
                $publicPath = Tool::getPath('public');
                //最后合成后的名字及路径
                // $files_names = 'uploads/'.date("YmdHis",time()).rand(100000,999999).'.'.$ext;
                makeDir($publicPath . $savPath);// 创建目录
                $files_names = $savPath . $saveName;
                //打开文件
                $fp = fopen($publicPath . $files_names,"ab");
                //循环读取临时文件，写入最终文件
                for($i = 0; $i < $count; $i++){
                    //临时文件路径及名称
                    $tmp_files = self::$source_tmp_path . md5($allBlockUuid).'-'.($i+1).'.tmp'; // 'uploads/tmp/'.md5($allBlockUuid).'-'.($i+1).'.tmp';
                    if(self::$cache_block == 2){
                        //方式一、打开临时文件
                        $handle = fopen($publicPath . $tmp_files,"rb");
                        //读取临时文件 写入最终文件
                         fwrite($fp, fread($handle, filesize($publicPath . $tmp_files)));
                        //关闭句柄 不关闭删除文件会出现没有权限
                         fclose($handle);
                        //方式一、删除临时文件
                         @unlink($tmp_files);
                    }else{
                        // 方式二、从缓存读取
                        fwrite($fp, Tool::getRedis('tmpFilesBin' . md5($tmp_files), 2));
                        // 方式二、删除缓存
                        Tool::setRedis('tmpFilesBin', md5($tmp_files), '', 2, 2); // 2 秒
                    }
                }
                //关闭句柄
                fclose($fp);
                $store_result = trim($files_names,'/');
                Log::info('上传文件日志-完成保存图片-分片',[$store_result]);
                // 清除缓存
                // 缓存0的扩展名
                Tool::setRedis('extend', md5($allBlockUuid), '', 2, 2); // 2秒
                // 文件大小处理
                Tool::setRedis('size', md5($allBlockUuid), 0, 2, 2); // 2秒
                // 缓存分存临时文件名
                Tool::setRedis('tmpFiles', md5($allBlockUuid), [], 2, 2); // 2秒

            }
            // 保存资源
            $saveData = array_merge($saveData ,[
                'resource_name' => $name,
                'resource_type' => $resource_type,
               // 'resource_note' => $resource_note,
                'resource_url' => $savPath . $saveName,
            ]);
            Log::info('上传文件日志-保存数据',[$saveData]);
            // $reslut = CommonBusiness::createApi(self::$model_name, $saveData, $company_id);
            $id = 0;
            $reslut = self::replaceById($request, $controller, $saveData, $id);
            $id = $reslut['id'] ?? '';
            Log::info('上传文件日志-reslut',[$reslut]);
            if(empty($id)){
                Log::info('上传文件日志-保存资源失败',[$id]);
                throws('保存资源失败!');
            }
        } catch ( \Exception $e) {
            throws($e->getMessage());
        }
        return [
            'id' => $id,// 资源id
            'name' => $name,// 文件名
            'savPath' => $savPath,// 保存路径 /结束
            'saveName' => $saveName,// 保存文件名称
            'store_result' => $store_result,// storeAs
            'info' => $reslut,// 资源表记录 一维
        ];
    }

    /**
     * 上传文件 --plupload
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $resource_type 资源类型
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function filePlupload(Request $request, Controller $controller, $resource_type = 0)
    {
        try{
            $result = self::uploadFile($request, $controller, $resource_type);
            $sucArr = [
                'result' => 'ok',// 文件上传成功
                'id' => $result['id'] , // 文件在服务器上的唯一标识
                'url'=> url($result['savPath'] . $result['saveName']),//'http://example.com/file-10001.jpg',// 文件的下载地址
                'store_result' => $result['store_result'],
                'data_list' => [
                    [
                        'id' => $result['id'],
                        'resource_name' => $result['name'],
                        'resource_url' => url($result['savPath'] . $result['saveName']),
                        'created_at' =>  date('Y-m-d H:i:s',time()),
                    ]
                ],
            ];
            Log::info('上传文件日志-成功',$sucArr);
            return $sucArr;
        } catch ( \Exception $e) {
            $errArr = [
                'result' => 'failed',// 文件上传失败
                'message' => $e->getMessage(),//'文件内容包含违规内容',//用于在界面上提示用户的消息
            ];
            Log::info('上传文件日志-失败',$errArr);
            return $errArr;
        }
    }
    /**
     * 上传文件 --plupload
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $resource_type 资源类型
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function fileSingleUpload(Request $request, Controller $controller, $resource_type = 0)
    {
        try{
            $result = self::uploadFile($request, $controller, $resource_type);
            $sucArr = [
                'id' => $result['id'] , // 文件在服务器上的唯一标识
                'url'=> url($result['savPath'] . $result['saveName']),//'http://example.com/file-10001.jpg',// 文件的下载地址
                'filePath' => $result['savPath'] . $result['saveName'],
                'store_result' => $result['store_result'],
                'resource_name' => $result['name'],
                'created_at' =>  date('Y-m-d H:i:s',time()),
            ];
            Log::info('上传文件日志-成功',$sucArr);
            return ajaxDataArr(1, $sucArr, '');

        } catch ( \Exception $e) {
            Log::info('上传文件日志-失败',[$e->getMessage()]);
            return ajaxDataArr(0, null,$e->getMessage());
        }
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
        $company_id = $controller->company_id;

        $id = Common::getInt($request, 'id');
        Common::judgeInitParams($request, 'id', $id);
        $resultDatas = CommonBusiness::ResourceDelById($id, $company_id);
        return ajaxDataArr(1, $resultDatas, '');
        // return self::delAjaxBase($request, $controller, self::$model_name);

    }

    /**
     * 根据id删除单条数据
     *
     * @param int $id 资源id
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delById($company_id, $id)
    {
        return CommonBusiness::ResourceDelById($id, $company_id);
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
}
