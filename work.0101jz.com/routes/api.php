<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// 文件上传
// Route::any('file/upload', 'IndexController@upload');
Route::any('upload', 'UploadController@index');
// Route::any('upload/test', 'UploadController@test');
Route::any('upload/ajax_del', 'UploadController@ajax_del');// 根据id删除文件
// excel
Route::get('excel/test','ExcelController@test');
Route::get('excel/export','ExcelController@export'); // 导出
Route::get('excel/import','ExcelController@import'); // 导入
//
// admin
// 登陆
Route::any('admin/ajax_login', 'admin\IndexController@ajax_login');// 登陆
Route::any('admin/ajax_password_save', 'admin\IndexController@ajax_password_save');// 修改密码
// 管理员
Route::any('admin/site_admin/ajax_alist', 'admin\SiteAdminController@ajax_alist');//ajax获得列表数据
Route::any('admin/site_admin/ajax_del', 'admin\SiteAdminController@ajax_del');// 删除
Route::any('admin/site_admin/ajax_save', 'admin\SiteAdminController@ajax_save');// 新加/修改
// 客户分类
Route::any('admin/customer_type/ajax_alist', 'admin\CustomerTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/customer_type/ajax_del', 'admin\CustomerTypeController@ajax_del');// 删除
Route::any('admin/customer_type/ajax_save', 'admin\CustomerTypeController@ajax_save');// 新加/修改
// 分数等级
Route::any('admin/core_grade/ajax_alist', 'admin\CoreGradeController@ajax_alist');//ajax获得列表数据
Route::any('admin/core_grade/ajax_del', 'admin\CoreGradeController@ajax_del');// 删除
Route::any('admin/core_grade/ajax_save', 'admin\CoreGradeController@ajax_save');// 新加/修改
// 业务标签
Route::any('admin/tags/ajax_alist', 'admin\TagsController@ajax_alist');//ajax获得列表数据
Route::any('admin/tags/ajax_del', 'admin\TagsController@ajax_del');// 删除
Route::any('admin/tags/ajax_save', 'admin\TagsController@ajax_save');// 新加/修改
// 业务时间
Route::any('admin/service_time/ajax_alist', 'admin\ServiceTimeController@ajax_alist');//ajax获得列表数据
Route::any('admin/service_time/ajax_del', 'admin\ServiceTimeController@ajax_del');// 删除
Route::any('admin/service_time/ajax_save', 'admin\ServiceTimeController@ajax_save');// 新加/修改

// 工单分类
Route::any('admin/work_type/ajax_alist', 'admin\WorkTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/work_type/ajax_get_child', 'admin\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::any('admin/work_type/ajax_del', 'admin\WorkTypeController@ajax_del');// 删除
Route::any('admin/work_type/ajax_save', 'admin\WorkTypeController@ajax_save');// 新加/修改

// 工单
Route::any('admin/work/ajax_alist', 'admin\WorkController@ajax_alist');//ajax获得列表数据
Route::any('admin/work/ajax_del', 'admin\WorkController@ajax_del');// 删除
Route::any('admin/work/ajax_save', 'admin\WorkController@ajax_save');// 新加/修改

// 来电分类
Route::any('admin/work_caller_type/ajax_alist', 'admin\WorkCallerTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/work_caller_type/ajax_del', 'admin\WorkCallerTypeController@ajax_del');// 删除
Route::any('admin/work_caller_type/ajax_save', 'admin\WorkCallerTypeController@ajax_save');// 新加/修改

// 系统
Route::any('admin/system/ajax_alist', 'admin\SystemController@ajax_alist');//ajax获得列表数据
Route::any('admin/system/ajax_del', 'admin\SystemController@ajax_del');// 删除
Route::any('admin/system/ajax_save', 'admin\SystemController@ajax_save');// 新加/修改

// 系统模块
Route::any('admin/system_module/ajax_alist', 'admin\SystemModuleController@ajax_alist');//ajax获得列表数据
Route::any('admin/system_module/ajax_get_child', 'admin\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::any('admin/system_module/ajax_del', 'admin\SystemModuleController@ajax_del');// 删除
Route::any('admin/system_module/ajax_save', 'admin\SystemModuleController@ajax_save');// 新加/修改

// 角色
Route::any('admin/roles/ajax_alist', 'admin\RolesController@ajax_alist');//ajax获得列表数据
Route::any('admin/roles/ajax_del', 'admin\RolesController@ajax_del');// 删除
Route::any('admin/roles/ajax_save', 'admin\RolesController@ajax_save');// 新加/修改

// 区域
Route::any('admin/area/ajax_alist', 'admin\AreaController@ajax_alist');//ajax获得列表数据
Route::any('admin/area/ajax_get_child', 'admin\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::any('admin/area/ajax_del', 'admin\AreaController@ajax_del');// 删除
Route::any('admin/area/ajax_save', 'admin\AreaController@ajax_save');// 新加/修改

// 部门
Route::any('admin/department/ajax_alist', 'admin\DepartmentController@ajax_alist');//ajax获得列表数据
Route::any('admin/department/ajax_get_child', 'admin\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::any('admin/department/ajax_del', 'admin\DepartmentController@ajax_del');// 删除
Route::any('admin/department/ajax_save', 'admin\DepartmentController@ajax_save');// 新加/修改

// 职位
Route::any('admin/position/ajax_alist', 'admin\PositionController@ajax_alist');//ajax获得列表数据
Route::any('admin/position/ajax_del', 'admin\PositionController@ajax_del');// 删除
Route::any('admin/position/ajax_save', 'admin\PositionController@ajax_save');// 新加/修改

// 知识分类
Route::any('admin/lore_type/ajax_alist', 'admin\LoreTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/lore_type/ajax_del', 'admin\LoreTypeController@ajax_del');// 删除
Route::any('admin/lore_type/ajax_save', 'admin\LoreTypeController@ajax_save');// 新加/修改

// 试题分类
Route::any('admin/subject_type/ajax_alist', 'admin\SubjectTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/subject_type/ajax_del', 'admin\SubjectTypeController@ajax_del');// 删除
Route::any('admin/subject_type/ajax_save', 'admin\SubjectTypeController@ajax_save');// 新加/修改
//同事
Route::any('admin/staff/ajax_alist', 'admin\StaffController@ajax_alist');//ajax获得列表数据
Route::any('admin/staff/ajax_del', 'admin\StaffController@ajax_del');// 删除
Route::any('admin/staff/ajax_save', 'admin\StaffController@ajax_save');// 新加/修改
Route::any('admin/staff/ajax_get_child', 'admin\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::any('admin/staff/ajax_get_areachild', 'admin\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::get('admin/staff/ajax_import_staff','admin\StaffController@ajax_import_staff'); // 导入员工

//反馈问题
Route::any('admin/problem/ajax_alist', 'admin\ProblemController@ajax_alist');// 大后台反馈问题
Route::any('admin/problem/reply_ajax_save', 'admin\ProblemController@reply_ajax_save');// 新加/修改

//客户
Route::any('admin/customer/ajax_alist', 'admin\CustomerController@ajax_alist');//ajax获得列表数据
Route::any('admin/customer/ajax_biaoji', 'admin\CustomerController@ajax_biaoji');//ajax标记

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
// 登陆
Route::any('manage/ajax_login', 'manage\IndexController@ajax_login');// 登陆
Route::any('manage/ajax_password_save', 'manage\IndexController@ajax_password_save');// 修改密码
//同事
Route::any('manage/staff/ajax_alist', 'manage\StaffController@ajax_alist');//ajax获得列表数据
Route::any('manage/staff/ajax_del', 'manage\StaffController@ajax_del');// 删除
Route::any('manage/staff/ajax_save', 'manage\StaffController@ajax_save');// 新加/修改
Route::any('manage/staff/ajax_get_child', 'manage\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::any('manage/staff/ajax_get_areachild', 'manage\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]

// 部门
Route::any('manage/department/ajax_get_child', 'manage\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//反馈问题 （liuxin）
Route::any('manage/problem/ajax_alist', 'manage\ProblemController@ajax_alist');//ajax获得反馈问题的列表数据
Route::any('manage/problem/reply_ajax_save', 'manage\ProblemController@reply_ajax_save');// 新加/修改

//客户
Route::any('manage/customer/ajax_alist', 'manage\CustomerController@ajax_alist');//ajax获得列表数据
Route::any('manage/customer/ajax_biaoji', 'manage\CustomerController@ajax_biaoji');//ajax标记

// 区域
Route::any('manage/area/ajax_get_child', 'manage\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]
// 工单分类
Route::any('manage/work_type/ajax_get_child', 'manage\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单
Route::any('manage/work/ajax_alist', 'manage\WorkController@ajax_alist');//ajax获得列表数据
Route::any('manage/work/ajax_del', 'manage\WorkController@ajax_del');// 删除
Route::any('manage/work/ajax_save', 'manage\WorkController@ajax_save');// 新加/修改

// 系统模块
Route::any('manage/system_module/ajax_get_child', 'manage\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-huawu 客服
Route::any('huawu/ajax_login', 'huawu\IndexController@ajax_login');// 登陆
Route::any('huawu/ajax_password_save', 'huawu\IndexController@ajax_password_save');// 修改密码
//同事
Route::any('huawu/staff/ajax_alist', 'huawu\StaffController@ajax_alist');//ajax获得列表数据
Route::any('huawu/staff/ajax_get_child', 'huawu\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::any('huawu/staff/ajax_get_areachild', 'huawu\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]


// 部门
Route::any('huawu/department/ajax_get_child', 'huawu\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//客户
Route::any('huawu/customer/ajax_alist', 'huawu\CustomerController@ajax_alist');//ajax获得列表数据
Route::any('huawu/customer/ajax_biaoji', 'huawu\CustomerController@ajax_biaoji');//ajax标记

// 区域
Route::any('huawu/area/ajax_get_child', 'huawu\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::any('huawu/work_type/ajax_get_child', 'huawu\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 系统模块
Route::any('huawu/system_module/ajax_get_child', 'huawu\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]
//工单
Route::any('huawu/work/ajax_save', 'huawu\WorkController@ajax_save');// 新加/修改
Route::any('huawu/work/ajax_alist', 'huawu\WorkController@ajax_alist');//ajax获得列表数据
Route::any('huawu/work/ajax_del', 'huawu\WorkController@ajax_del');// 删除
Route::any('huawu/work/reply_ajax_save', 'huawu\WorkController@reply_ajax_save');// 回访新加/修改
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-weixiu 维修
// 登陆
Route::any('weixiu/ajax_login', 'weixiu\IndexController@ajax_login');// 登陆
Route::any('weixiu/ajax_password_save', 'weixiu\IndexController@ajax_password_save');// 修改密码
//同事
Route::any('weixiu/staff/ajax_alist', 'weixiu\StaffController@ajax_alist');//ajax获得列表数据
Route::any('weixiu/staff/ajax_get_child', 'weixiu\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::any('weixiu/staff/ajax_get_areachild', 'weixiu\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]

// 部门
Route::any('weixiu/department/ajax_get_child', 'weixiu\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//客户
    // piwik.com
Route::any('weixiu/customer/ajax_alist', 'weixiu\CustomerController@ajax_alist');//ajax获得列表数据
Route::any('weixiu/customer/ajax_biaoji', 'weixiu\CustomerController@ajax_biaoji');//ajax标记


// 区域
Route::any('weixiu/area/ajax_get_child', 'weixiu\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::any('weixiu/work_type/ajax_get_child', 'weixiu\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单
Route::any('weixiu/work/ajax_alist', 'weixiu\WorkController@ajax_alist');//ajax获得列表数据
Route::any('weixiu/work/ajax_del', 'weixiu\WorkController@ajax_del');// 删除
Route::any('weixiu/work/ajax_save', 'weixiu\WorkController@ajax_save');// 新加/修改
Route::any('weixiu/work/ajax_doing_list', 'weixiu\WorkController@ajax_doing_list');// 根据状态获得工单信息
Route::any('weixiu/work/ajax_sure', 'weixiu\WorkController@ajax_sure');// 确认工单地址
Route::any('weixiu/work/ajax_win', 'weixiu\WorkController@ajax_win');// 工单结单地址

// 系统模块
Route::any('weixiu/system_module/ajax_get_child', 'weixiu\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//问题
// Route::any('weixiu/problem/ajax_gettype', 'weixiu\ProblemController@ajax_gettype');//ajax获得二级分类（类型）数据
// Route::any('weixiu/problem/ajax_getarea', 'weixiu\ProblemController@ajax_getarea');//ajax获得二级地址数据
// Route::any('weixiu/problem/ajax_problem_add', 'weixiu\ProblemController@ajax_problem_add');//ajax获得二级地址数据
Route::any('weixiu/problem/ajax_alist', 'weixiu\ProblemController@ajax_alist');//ajax获得列表数据
Route::any('weixiu/problem/ajax_save', 'weixiu\ProblemController@ajax_save');// 新加/修改

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::any('app/ajax_login', 'app\IndexController@ajax_login');// 登陆




//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//m
Route::any('m/ajax_login', 'm\IndexController@ajax_login');// 登陆
Route::any('m/ajax_password_save', 'm\IndexController@ajax_password_save');// 修改密码
//同事
Route::any('m/staff/ajax_alist', 'm\StaffController@ajax_alist');//ajax获得列表数据
Route::any('m/staff/ajax_get_child', 'm\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::any('m/staff/ajax_get_areachild', 'm\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]

// 部门
Route::any('m/department/ajax_get_child', 'm\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 区域
Route::any('m/area/ajax_get_child', 'm\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::any('m/work_type/ajax_get_child', 'm\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单
Route::any('m/work/ajax_alist', 'm\WorkController@ajax_alist');//ajax获得列表数据
Route::any('m/work/ajax_del', 'm\WorkController@ajax_del');// 删除
Route::any('m/work/ajax_save', 'm\WorkController@ajax_save');// 新加/修改
Route::any('m/work/ajax_doing_list', 'm\WorkController@ajax_doing_list');// 根据状态获得工单信息
Route::any('m/work/ajax_sure', 'm\WorkController@ajax_sure');// 确认工单地址
Route::any('m/work/ajax_win', 'm\WorkController@ajax_win');// 工单结单地址

// 系统模块
Route::any('m/system_module/ajax_get_child', 'm\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//问题
//Route::any('m/problem/ajax_gettype', 'm\ProblemController@ajax_gettype');//ajax获得二级分类（类型）数据
//Route::any('m/problem/ajax_getarea', 'm\ProblemController@ajax_getarea');//ajax获得二级地址数据
//Route::any('m/problem/ajax_problem_add', 'm\ProblemController@ajax_problem_add');//ajax获得二级地址数据
Route::any('m/problem/ajax_save', 'm\ProblemController@ajax_save');// 新加/修改
// 消息确认
Route::any('m/msg/ajax_save', 'm\SiteMsgController@ajax_sure');// 新加/修改




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::post('file/upload', function(\Illuminate\Http\Request $request) {
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $photo = $request->file('photo');
        $extension = $photo->extension();
        //$store_result = $photo->store('photo');
        $store_result = $photo->storeAs('photo', 'test.jpg');
        $output = [
            'extension' => $extension,
            'store_result' => $store_result
        ];
        print_r($output);exit();
    }
    exit('未获取到上传文件或上传过程出错');
});
*/