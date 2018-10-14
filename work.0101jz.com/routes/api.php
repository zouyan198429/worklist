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
// 文件上传 any(
// Route::post('file/upload', 'IndexController@upload');
Route::post('upload', 'UploadController@index');
// Route::post('upload/test', 'UploadController@test');
// excel
Route::get('excel/test','ExcelController@test');
Route::get('excel/export','ExcelController@export'); // 导出
Route::get('excel/import','ExcelController@import'); // 导入

//
// admin
// 上传图片
Route::post('admin/upload', 'admin\UploadController@index');
Route::post('admin/upload/ajax_del', 'admin\UploadController@ajax_del');// 根据id删除文件

// 登陆
Route::post('admin/ajax_login', 'admin\IndexController@ajax_login');// 登陆
Route::post('admin/ajax_password_save', 'admin\IndexController@ajax_password_save');// 修改密码
// 管理员
Route::post('admin/site_admin/ajax_alist', 'admin\SiteAdminController@ajax_alist');//ajax获得列表数据
Route::post('admin/site_admin/ajax_del', 'admin\SiteAdminController@ajax_del');// 删除
Route::post('admin/site_admin/ajax_save', 'admin\SiteAdminController@ajax_save');// 新加/修改
// 客户分类
Route::post('admin/customer_type/ajax_alist', 'admin\CustomerTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/customer_type/ajax_del', 'admin\CustomerTypeController@ajax_del');// 删除
Route::post('admin/customer_type/ajax_save', 'admin\CustomerTypeController@ajax_save');// 新加/修改
// 分数等级
Route::post('admin/core_grade/ajax_alist', 'admin\CoreGradeController@ajax_alist');//ajax获得列表数据
Route::post('admin/core_grade/ajax_del', 'admin\CoreGradeController@ajax_del');// 删除
Route::post('admin/core_grade/ajax_save', 'admin\CoreGradeController@ajax_save');// 新加/修改
// 业务标签
Route::post('admin/tags/ajax_alist', 'admin\TagsController@ajax_alist');//ajax获得列表数据
Route::post('admin/tags/ajax_del', 'admin\TagsController@ajax_del');// 删除
Route::post('admin/tags/ajax_save', 'admin\TagsController@ajax_save');// 新加/修改
// 业务时间
Route::post('admin/service_time/ajax_alist', 'admin\ServiceTimeController@ajax_alist');//ajax获得列表数据
Route::post('admin/service_time/ajax_del', 'admin\ServiceTimeController@ajax_del');// 删除
Route::post('admin/service_time/ajax_save', 'admin\ServiceTimeController@ajax_save');// 新加/修改

// 工单分类
Route::post('admin/work_type/ajax_alist', 'admin\WorkTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/work_type/ajax_get_child', 'admin\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::post('admin/work_type/ajax_del', 'admin\WorkTypeController@ajax_del');// 删除
Route::post('admin/work_type/ajax_save', 'admin\WorkTypeController@ajax_save');// 新加/修改

// 工单
Route::post('admin/work/ajax_alist', 'admin\WorkController@ajax_alist');//ajax获得列表数据
Route::post('admin/work/ajax_del', 'admin\WorkController@ajax_del');// 删除
Route::post('admin/work/ajax_save', 'admin\WorkController@ajax_save');// 新加/修改
Route::post('admin/work/ajax_status_count', 'admin\WorkController@ajax_status_count');// 工单状态统计
Route::post('admin/work/ajax_work_count', 'admin\WorkController@ajax_work_count');// 工单统计
Route::post('admin/work/ajax_repair_count', 'admin\WorkController@ajax_repair_count');// 工单维修统计

// 来电分类
Route::post('admin/work_caller_type/ajax_alist', 'admin\WorkCallerTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/work_caller_type/ajax_del', 'admin\WorkCallerTypeController@ajax_del');// 删除
Route::post('admin/work_caller_type/ajax_save', 'admin\WorkCallerTypeController@ajax_save');// 新加/修改

// 系统
Route::post('admin/system/ajax_alist', 'admin\SystemController@ajax_alist');//ajax获得列表数据
Route::post('admin/system/ajax_del', 'admin\SystemController@ajax_del');// 删除
Route::post('admin/system/ajax_save', 'admin\SystemController@ajax_save');// 新加/修改

// 系统模块
Route::post('admin/system_module/ajax_alist', 'admin\SystemModuleController@ajax_alist');//ajax获得列表数据
Route::post('admin/system_module/ajax_get_child', 'admin\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::post('admin/system_module/ajax_del', 'admin\SystemModuleController@ajax_del');// 删除
Route::post('admin/system_module/ajax_save', 'admin\SystemModuleController@ajax_save');// 新加/修改

// 角色
Route::post('admin/roles/ajax_alist', 'admin\RolesController@ajax_alist');//ajax获得列表数据
Route::post('admin/roles/ajax_del', 'admin\RolesController@ajax_del');// 删除
Route::post('admin/roles/ajax_save', 'admin\RolesController@ajax_save');// 新加/修改

// 区域
Route::post('admin/area/ajax_alist', 'admin\AreaController@ajax_alist');//ajax获得列表数据
Route::post('admin/area/ajax_get_child', 'admin\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::post('admin/area/ajax_del', 'admin\AreaController@ajax_del');// 删除
Route::post('admin/area/ajax_save', 'admin\AreaController@ajax_save');// 新加/修改

// 部门
Route::post('admin/department/ajax_alist', 'admin\DepartmentController@ajax_alist');//ajax获得列表数据
Route::post('admin/department/ajax_get_child', 'admin\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::post('admin/department/ajax_del', 'admin\DepartmentController@ajax_del');// 删除
Route::post('admin/department/ajax_save', 'admin\DepartmentController@ajax_save');// 新加/修改

// 职位
Route::post('admin/position/ajax_alist', 'admin\PositionController@ajax_alist');//ajax获得列表数据
Route::post('admin/position/ajax_del', 'admin\PositionController@ajax_del');// 删除
Route::post('admin/position/ajax_save', 'admin\PositionController@ajax_save');// 新加/修改

// 知识分类
Route::post('admin/lore_type/ajax_alist', 'admin\LoreTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/lore_type/ajax_del', 'admin\LoreTypeController@ajax_del');// 删除
Route::post('admin/lore_type/ajax_save', 'admin\LoreTypeController@ajax_save');// 新加/修改

//通知公告
Route::post('admin/notice/ajax_alist', 'admin\NoticeController@ajax_alist');//ajax获得列表数据
Route::post('admin/notice/ajax_del', 'admin\NoticeController@ajax_del');// 删除
Route::post('admin/notice/ajax_save', 'admin\NoticeController@ajax_save');// 新加/修改
//学习
Route::post('admin/lore/ajax_alist', 'admin\LoreController@ajax_alist');//ajax获得列表数据
Route::post('admin/lore/ajax_del', 'admin\LoreController@ajax_del');// 删除
Route::post('admin/lore/ajax_save', 'admin\LoreController@ajax_save');// 新加/修改

// 试题分类
Route::post('admin/subject_type/ajax_alist', 'admin\SubjectTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/subject_type/ajax_del', 'admin\SubjectTypeController@ajax_del');// 删除
Route::post('admin/subject_type/ajax_save', 'admin\SubjectTypeController@ajax_save');// 新加/修改
//同事
Route::post('admin/staff/ajax_alist', 'admin\StaffController@ajax_alist');//ajax获得列表数据
Route::post('admin/staff/ajax_del', 'admin\StaffController@ajax_del');// 删除
Route::post('admin/staff/ajax_save', 'admin\StaffController@ajax_save');// 新加/修改
Route::post('admin/staff/ajax_get_child', 'admin\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_get_areachild', 'admin\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_import_staff','admin\StaffController@ajax_import_staff'); // 导入员工

// 反馈分类
Route::post('admin/problem_type/ajax_alist', 'admin\ProblemTypeController@ajax_alist');//ajax获得列表数据
Route::post('admin/problem_type/ajax_get_child', 'admin\ProblemTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]
Route::post('admin/problem_type/ajax_del', 'admin\ProblemTypeController@ajax_del');// 删除
Route::post('admin/problem_type/ajax_save', 'admin\ProblemTypeController@ajax_save');// 新加/修改

//反馈问题
Route::post('admin/problem/ajax_alist', 'admin\ProblemController@ajax_alist');// 大后台反馈问题
Route::post('admin/problem/reply_ajax_save', 'admin\ProblemController@reply_ajax_save');// 新加/修改

//客户
Route::post('admin/customer/ajax_alist', 'admin\CustomerController@ajax_alist');//ajax获得列表数据
Route::post('admin/customer/ajax_biaoji', 'admin\CustomerController@ajax_biaoji');//ajax标记
Route::post('admin/customer/ajax_is_tab', 'admin\CustomerController@ajax_is_tab');//ajax标记

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
// 上传图片
Route::post('manage/upload', 'manage\UploadController@index');
Route::post('manage/upload/ajax_del', 'manage\UploadController@ajax_del');// 根据id删除文件

// 登陆
Route::post('manage/ajax_login', 'manage\IndexController@ajax_login');// 登陆
Route::post('manage/ajax_password_save', 'manage\IndexController@ajax_password_save');// 修改密码
//同事
Route::post('manage/staff/ajax_alist', 'manage\StaffController@ajax_alist');//ajax获得列表数据
Route::post('manage/staff/ajax_del', 'manage\StaffController@ajax_del');// 删除
Route::post('manage/staff/ajax_save', 'manage\StaffController@ajax_save');// 新加/修改
Route::post('manage/staff/ajax_get_child', 'manage\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/staff/ajax_get_areachild', 'manage\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
//通知公告
Route::post('manage/notice/ajax_alist', 'manage\NoticeController@ajax_alist');//ajax获得列表数据
Route::post('manage/notice/ajax_del', 'manage\NoticeController@ajax_del');// 删除
Route::post('manage/notice/ajax_save', 'manage\NoticeController@ajax_save');// 新加/修改
//学习
Route::post('manage/lore/ajax_alist', 'manage\LoreController@ajax_alist');//ajax获得列表数据
Route::post('manage/lore/ajax_del', 'manage\LoreController@ajax_del');// 删除
Route::post('manage/lore/ajax_save', 'manage\LoreController@ajax_save');// 新加/修改

// 部门
Route::post('manage/department/ajax_get_child', 'manage\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 反馈分类
Route::post('manage/problem_type/ajax_get_child', 'manage\ProblemTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//反馈问题 （liuxin）
Route::post('manage/problem/ajax_alist', 'manage\ProblemController@ajax_alist');//ajax获得反馈问题的列表数据
Route::post('manage/problem/reply_ajax_save', 'manage\ProblemController@reply_ajax_save');// 新加/修改

//客户
Route::post('manage/customer/ajax_alist', 'manage\CustomerController@ajax_alist');//ajax获得列表数据
Route::post('manage/customer/ajax_biaoji', 'manage\CustomerController@ajax_biaoji');//ajax标记
Route::post('manage/customer/ajax_is_tab', 'manage\CustomerController@ajax_is_tab');//ajax标记

// 区域
Route::post('manage/area/ajax_get_child', 'manage\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]
// 工单分类
Route::post('manage/work_type/ajax_get_child', 'manage\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单
Route::post('manage/work/ajax_alist', 'manage\WorkController@ajax_alist');//ajax获得列表数据
Route::post('manage/work/ajax_del', 'manage\WorkController@ajax_del');// 删除
Route::post('manage/work/ajax_save', 'manage\WorkController@ajax_save');// 新加/修改
Route::post('manage/work/ajax_status_count', 'manage\WorkController@ajax_status_count');// 工单状态统计
Route::post('manage/work/ajax_work_count', 'manage\WorkController@ajax_work_count');// 工单统计
Route::post('manage/work/ajax_repair_count', 'manage\WorkController@ajax_repair_count');// 工单维修统计

// 系统模块
Route::post('manage/system_module/ajax_get_child', 'manage\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// 上传图片
Route::post('huawu/upload', 'huawu\UploadController@index');
Route::post('huawu/upload/ajax_del', 'huawu\UploadController@ajax_del');// 根据id删除文件

//web-huawu 客服
Route::post('huawu/ajax_login', 'huawu\IndexController@ajax_login');// 登陆
Route::post('huawu/ajax_password_save', 'huawu\IndexController@ajax_password_save');// 修改密码
//同事
Route::post('huawu/staff/ajax_alist', 'huawu\StaffController@ajax_alist');//ajax获得列表数据
Route::post('huawu/staff/ajax_get_child', 'huawu\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('huawu/staff/ajax_get_areachild', 'huawu\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]


// 部门
Route::post('huawu/department/ajax_get_child', 'huawu\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//客户
Route::post('huawu/customer/ajax_alist', 'huawu\CustomerController@ajax_alist');//ajax获得列表数据
Route::post('huawu/customer/ajax_biaoji', 'huawu\CustomerController@ajax_biaoji');//ajax标记
Route::post('huawu/customer/ajax_is_tab', 'huawu\CustomerController@ajax_is_tab');//ajax标记

//通知公告
Route::post('huawu/notice/ajax_alist', 'huawu\NoticeController@ajax_alist');//ajax获得列表数据
//学习
Route::post('huawu/lore/ajax_alist', 'huawu\LoreController@ajax_alist');//ajax获得列表数据

// 区域
Route::post('huawu/area/ajax_get_child', 'huawu\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 反馈分类
Route::post('huawu/problem_type/ajax_get_child', 'huawu\ProblemTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::post('huawu/work_type/ajax_get_child', 'huawu\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 系统模块
Route::post('huawu/system_module/ajax_get_child', 'huawu\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]
//工单
Route::post('huawu/work/ajax_save', 'huawu\WorkController@ajax_save');// 新加/修改
Route::post('huawu/work/ajax_alist', 'huawu\WorkController@ajax_alist');//ajax获得列表数据
Route::post('huawu/work/ajax_del', 'huawu\WorkController@ajax_del');// 删除
Route::post('huawu/work/reply_ajax_save', 'huawu\WorkController@reply_ajax_save');// 回访新加/修改
Route::post('huawu/work/ajax_status_count', 'huawu\WorkController@ajax_status_count');// 工单状态统计
Route::post('huawu/work/ajax_work_count', 'huawu\WorkController@ajax_work_count');// 工单统计
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-weixiu 维修
// 上传图片
Route::post('weixiu/upload', 'weixiu\UploadController@index');
Route::post('weixiu/upload/ajax_del', 'weixiu\UploadController@ajax_del');// 根据id删除文件
// 登陆
Route::post('weixiu/ajax_login', 'weixiu\IndexController@ajax_login');// 登陆
Route::post('weixiu/ajax_password_save', 'weixiu\IndexController@ajax_password_save');// 修改密码
//同事
Route::post('weixiu/staff/ajax_alist', 'weixiu\StaffController@ajax_alist');//ajax获得列表数据
Route::post('weixiu/staff/ajax_get_child', 'weixiu\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('weixiu/staff/ajax_get_areachild', 'weixiu\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]

// 部门
Route::post('weixiu/department/ajax_get_child', 'weixiu\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//通知公告
Route::post('weixiu/notice/ajax_alist', 'weixiu\NoticeController@ajax_alist');//ajax获得列表数据
//学习
Route::post('weixiu/lore/ajax_alist', 'weixiu\LoreController@ajax_alist');//ajax获得列表数据

//客户
    // piwik.com
Route::post('weixiu/customer/ajax_alist', 'weixiu\CustomerController@ajax_alist');//ajax获得列表数据
Route::post('weixiu/customer/ajax_biaoji', 'weixiu\CustomerController@ajax_biaoji');//ajax标记
Route::post('weixiu/customer/ajax_is_tab', 'weixiu\CustomerController@ajax_is_tab');//ajax标记


// 区域
Route::post('weixiu/area/ajax_get_child', 'weixiu\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 反馈分类
Route::post('weixiu/problem_type/ajax_get_child', 'weixiu\ProblemTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::post('weixiu/work_type/ajax_get_child', 'weixiu\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单
Route::post('weixiu/work/ajax_alist', 'weixiu\WorkController@ajax_alist');//ajax获得列表数据
Route::post('weixiu/work/ajax_del', 'weixiu\WorkController@ajax_del');// 删除
Route::post('weixiu/work/ajax_save', 'weixiu\WorkController@ajax_save');// 新加/修改
Route::post('weixiu/work/ajax_doing_list', 'weixiu\WorkController@ajax_doing_list');// 根据状态获得工单信息
Route::post('weixiu/work/ajax_sure', 'weixiu\WorkController@ajax_sure');// 确认工单地址
Route::post('weixiu/work/ajax_win', 'weixiu\WorkController@ajax_win');// 工单结单地址
Route::post('weixiu/work/ajax_status_count', 'weixiu\WorkController@ajax_status_count');// 工单状态统计
Route::post('weixiu/work/ajax_repair_count', 'weixiu\WorkController@ajax_repair_count');// 工单维修统计

// 系统模块
Route::post('weixiu/system_module/ajax_get_child', 'weixiu\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//问题
// Route::post('weixiu/problem/ajax_gettype', 'weixiu\ProblemController@ajax_gettype');//ajax获得二级分类（类型）数据
// Route::post('weixiu/problem/ajax_getarea', 'weixiu\ProblemController@ajax_getarea');//ajax获得二级地址数据
// Route::post('weixiu/problem/ajax_problem_add', 'weixiu\ProblemController@ajax_problem_add');//ajax获得二级地址数据
Route::post('weixiu/problem/ajax_alist', 'weixiu\ProblemController@ajax_alist');//ajax获得列表数据
Route::post('weixiu/problem/ajax_save', 'weixiu\ProblemController@ajax_save');// 新加/修改

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::post('app/ajax_login', 'app\IndexController@ajax_login');// 登陆




//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//m
// 上传图片
Route::post('m/upload', 'm\UploadController@index');
Route::post('m/upload/ajax_del', 'm\UploadController@ajax_del');// 根据id删除文件
Route::post('m/ajax_login', 'm\IndexController@ajax_login');// 登陆
Route::post('m/ajax_password_save', 'm\IndexController@ajax_password_save');// 修改密码
//同事
Route::post('m/staff/ajax_alist', 'm\StaffController@ajax_alist');//ajax获得列表数据
Route::post('m/staff/ajax_get_child', 'm\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('m/staff/ajax_get_areachild', 'm\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]

// 部门
Route::post('m/department/ajax_get_child', 'm\DepartmentController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 区域
Route::post('m/area/ajax_get_child', 'm\AreaController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 工单分类
Route::post('m/work_type/ajax_get_child', 'm\WorkTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//通知公告
Route::post('m/notice/ajax_alist', 'm\NoticeController@ajax_alist');//ajax获得列表数据
//学习
Route::post('m/lore/ajax_alist', 'm\LoreController@ajax_alist');//ajax获得列表数据

// 工单
Route::post('m/work/ajax_alist', 'm\WorkController@ajax_alist');//ajax获得列表数据
Route::post('m/work/ajax_del', 'm\WorkController@ajax_del');// 删除
Route::post('m/work/ajax_save', 'm\WorkController@ajax_save');// 新加/修改
Route::post('m/work/ajax_doing_list', 'm\WorkController@ajax_doing_list');// 根据状态获得工单信息
Route::post('m/work/ajax_sure', 'm\WorkController@ajax_sure');// 确认工单地址
Route::post('m/work/ajax_win', 'm\WorkController@ajax_win');// 工单结单地址
Route::post('m/work/ajax_status_count', 'm\WorkController@ajax_status_count');// 工单状态统计
Route::post('m/work/ajax_work_sum', 'm\WorkController@ajax_work_sum');// 工单数量统计

// 系统模块
Route::post('m/system_module/ajax_get_child', 'm\SystemModuleController@ajax_get_child');// 获得子类部门数组[kv一维数组]

// 反馈分类
Route::post('m/problem_type/ajax_get_child', 'm\ProblemTypeController@ajax_get_child');// 获得子类部门数组[kv一维数组]

//问题
//Route::post('m/problem/ajax_gettype', 'm\ProblemController@ajax_gettype');//ajax获得二级分类（类型）数据
//Route::post('m/problem/ajax_getarea', 'm\ProblemController@ajax_getarea');//ajax获得二级地址数据
//Route::post('m/problem/ajax_problem_add', 'm\ProblemController@ajax_problem_add');//ajax获得二级地址数据
Route::post('m/problem/ajax_save', 'm\ProblemController@ajax_save');// 新加/修改
// 消息确认
Route::post('m/msg/ajax_save', 'm\SiteMsgController@ajax_sure');// 新加/修改
Route::post('m/msg/ajax_alist', 'm\SiteMsgController@ajax_alist');// ajax获得列表数据




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