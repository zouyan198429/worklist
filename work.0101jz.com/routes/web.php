<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


//Route::get('/test', 'IndexController@test');// 测试
Route::get('/test2', 'IndexController@test2');// 测试
Route::get('/', 'IndexController@index');// 首页
Route::get('reg', 'IndexController@reg');// 注册
Route::get('{company_id}/login', 'IndexController@login');// 登陆
Route::get('login', 'IndexController@login');// 登陆
//Route::get('logout', 'IndexController@logout');// 注销
//Route::get('404', 'IndexController@err404');// 404错误

// 百度上传测试
Route::get('weixiu/webuploader', 'weixiu\WebUploaderController@index');// 上传前端html页


// admin
Route::get('admin', 'admin\IndexController@index');//index.html  首页
Route::get('admin/{company_id}/login', 'admin\IndexController@login');//login.html 登录
Route::get('admin/login', 'admin\IndexController@login');//login.html 登录
Route::get('admin/{company_id}/logout', 'admin\IndexController@logout');// 注销
Route::get('admin/logout', 'admin\IndexController@logout');// 注销
Route::get('admin/password', 'admin\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('admin/info', 'admin\IndexController@info');//myinfo.html 个人信息--显示

  //管理员
Route::get('admin/site_admin', 'admin\SiteAdminController@index');//class_admin.html  管理员管理
Route::get('admin/site_admin/add/{id}', 'admin\SiteAdminController@add');// 管理员管理--添加

// 系统基本设置
// 来电分类
Route::get('admin/work_caller_type', 'admin\WorkCallerTypeController@index');//class_call.html 来电分类
Route::get('admin/work_caller_type/add/{id}', 'admin\WorkCallerTypeController@add');// 来电分类--添加
// 区域
Route::get('admin/area', 'admin\AreaController@index');//class_quyu.html  区域管理
Route::get('admin/area/add/{id}', 'admin\AreaController@add');// 区域管理--添加

//业务标签
Route::get('admin/tags', 'admin\TagsController@index');//class_tags.html  业务标签
Route::get('admin/tags/add/{id}', 'admin\TagsController@add');//业务标签--添加
//业务时间
Route::get('admin/service_time', 'admin\ServiceTimeController@index');//  业务时间
Route::get('admin/service_time/add/{id}', 'admin\ServiceTimeController@add');//业务时间--添加

// 分数等级
Route::get('admin/core_grade', 'admin\CoreGradeController@index');//  分数等级管理
Route::get('admin/core_grade/add/{id}', 'admin\CoreGradeController@add');// 分数等级管理--添加

// 系统
Route::get('admin/system', 'admin\SystemController@index');//  系统管理
Route::get('admin/system/add/{id}', 'admin\SystemController@add');// 系统管理--添加

// 系统模块
Route::get('admin/system_module', 'admin\SystemModuleController@index');//  系统模块
Route::get('admin/system_module/add/{id}', 'admin\SystemModuleController@add');// 系统模块--添加


// 角色/权限
Route::get('admin/roles', 'admin\RolesController@index');//class_jiaose.html 角色/权限管理//
Route::get('admin/roles/add/{id}', 'admin\RolesController@add');// 系统模块--添加

// 部门
Route::get('admin/department', 'admin\DepartmentController@index');//class_bumen.html  部门管理
Route::get('admin/department/add/{id}', 'admin\DepartmentController@add');// 部门管理--添加

// 职位
Route::get('admin/position', 'admin\PositionController@index');//  职位管理
Route::get('admin/position/add/{id}', 'admin\PositionController@add');// 职位管理--添加

// 员工
Route::get('admin/staff', 'admin\StaffController@index');//Colleague.html 我的同事
Route::get('admin/staff/add/{id}', 'admin\StaffController@add');//m_staff_add.html 添加员工
Route::get('admin/staff/select', 'admin\StaffController@select');// 同事选择-弹窗

Route::get('admin/staff/export', 'admin\StaffController@export');//colleague.html 我的同事--导出
Route::get('admin/staff/import_template', 'admin\StaffController@import_template');//colleague.html 我的同事--导入模版
//客户
Route::get('admin/customer', 'admin\CustomerController@index');//customer_all.html 客户管理
Route::get('admin/customer/day_count', 'admin\CustomerController@dayCount');//achievement.html  我的客户-按日统计--*
//公司分站
Route::get('admin/company', 'admin\CompanyController@index');//class_kehu.html 公司分站
Route::get('admin/company/add/{id}', 'admin\CompanyController@add');// 公司分站--添加
//客户类型
Route::get('admin/customer_type', 'admin\CustomerTypeController@index');//class_kehu.html 客户分类
Route::get('admin/customer_type/add/{id}', 'admin\CustomerTypeController@add');// 客户分类--添加

//工单
// 工单分类
Route::get('admin/work_type', 'admin\WorkTypeController@index');//class_order.html 工单分类
Route::get('admin/work_type/add/{id}', 'admin\WorkTypeController@add');// 工单分类--添加

Route::get('admin/work', 'admin\WorkController@index');//work_monitor.html 工单管理
Route::get('admin/work/info/{id}', 'admin\WorkController@info');//work_add.html 工单--详情
Route::get('admin/work/export', 'admin\WorkController@export');//--导出
//统计
Route::get('admin/count_call', 'admin\CountCallController@index');//count_call.html  来电统计//
Route::get('admin/count_customer', 'admin\CountCustomerController@index');//count_Customer.html  来电统计-客户//
Route::get('admin/count_repair', 'admin\CountRepairController@index');//count_Repair.html 来电统计-维修改数量

//知识
// 知识分类
Route::get('admin/lore_type', 'admin\LoreTypeController@index');//know_class.html 知识分类
Route::get('admin/lore_type/add/{id}', 'admin\LoreTypeController@add');// 知识分类--添加

// 知识
Route::get('admin/lore/add/{id}', 'admin\LoreController@add');//know_add.html 在线学习-添加
Route::get('admin/lore', 'admin\LoreController@index');//know_list.html  在线学习-列表
Route::get('admin/lore/info/{id}', 'admin\LoreController@info');//know_view.html 在线学习-详情
//通知公告
Route::get('admin/notice/add/{id}', 'admin\NoticeController@add');//添加
Route::get('admin/notice', 'admin\NoticeController@index');//列表
Route::get('admin/notice/info/{id}', 'admin\NoticeController@info');//详情

// 反馈分类
Route::get('admin/problem_type', 'admin\ProblemTypeController@index');//class_order.html 反馈分类
Route::get('admin/problem_type/add/{id}', 'admin\ProblemTypeController@add');// 反馈分类--添加

//反馈
Route::get('admin/problem', 'admin\ProblemController@index');//problem.html 反馈问题
Route::get('admin/problem/reply/{id}', 'admin\ProblemController@reply');//m_problem.html 反馈问题 - 回复

//考次试卷
Route::get('admin/exam/add/{id}', 'admin\ExamController@add');//x_examination_add.html 试题添加
Route::get('admin/exam', 'admin\ExamController@index');//x_examination_list.html 试题管理
Route::get('admin/exam/info/{id}', 'admin\ExamController@info');//-详情
Route::get('admin/exam/export', 'admin\ExamController@export');//--导出
Route::get('admin/exam/info/{id}', 'admin\ExamController@info');//
Route::get('admin/exam/exportStaff', 'admin\ExamController@exportStaff');//--导出考试结果

//试题
Route::get('admin/subject', 'admin\SubjectController@index');//x_questions.html 试题管理
Route::get('admin/subject/add/{id}', 'admin\SubjectController@add');//x_questions_add.html 试题添加
Route::get('admin/subject/info/{id}', 'admin\SubjectController@info');//-详情
Route::get('admin/subject/export', 'admin\SubjectController@export');//--导出
Route::get('admin/subject/import_template', 'admin\SubjectController@import_template');//--导入模版
Route::get('admin/subject/select', 'admin\SubjectController@select');//x_questions.html 试题选择-弹窗

// 试题分类
Route::get('admin/subject_type', 'admin\SubjectTypeController@index');//x_questions_class.html 试题分类
Route::get('admin/subject_type/add/{id}', 'admin\SubjectTypeController@add');// 试题分类--添加

// 试卷
Route::get('admin/paper', 'admin\PaperController@index');//x_testpaper_list.html 试卷列表
Route::get('admin/paper/add/{id}', 'admin\PaperController@add');//x_testpaper_add.html 试卷添加
Route::get('admin/paper/info/{id}', 'admin\PaperController@info');//-详情
Route::get('admin/paper/export', 'admin\PaperController@export');//--导出
Route::get('admin/paper/select', 'admin\PaperController@select');// 试卷选择-弹窗

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
Route::get('manage/testUpfile', 'manage\IndexController@testUpfile');// 测试
Route::get('manage', 'manage\IndexController@index');//main_admin.html -首页
Route::get('manage/{company_id}/login', 'manage\IndexController@login');//login.html 登陆
Route::get('manage/login', 'manage\IndexController@login');//login.html 登陆
Route::get('manage/{company_id}/logout', 'manage\IndexController@logout');// 注销
Route::get('manage/logout', 'manage\IndexController@logout');// 注销
Route::get('manage/hot', 'manage\IndexController@index_hot');//work_hot.html 首页
Route::get('manage/info', 'manage\IndexController@info');//myinfo.html 个人信息
Route::get('manage/password', 'manage\IndexController@password');//psdmodify.html  个人信息-修改密码

// 统计
Route::get('manage/count_call', 'manage\CountCallController@index');//count_call.html 来电统计 -来电
Route::get('manage/count_customer', 'manage\CountCustomerController@index');///count_Customer.html 来电统计--客户
Route::get('manage/count_repair', 'manage\CountRepairController@index');//count_Repair.html 来电统计
//学习
Route::get('manage/lore/add/{id}', 'manage\LoreController@add');//know_add.html 在线学习-添加
Route::get('manage/lore', 'manage\LoreController@index');//know_list.html  在线学习-列表
Route::get('manage/lore/list', 'manage\LoreController@list');////study.html 在线学习
Route::get('manage/lore/info/{id}', 'manage\LoreController@info');//know_view.html 在线学习-详情
//通知公告
Route::get('manage/notice/add/{id}', 'manage\NoticeController@add');//添加
Route::get('manage/notice', 'manage\NoticeController@index');//列表
Route::get('manage/notice/info/{id}', 'manage\NoticeController@info');//详情
//客户
Route::get('manage/customer', 'manage\CustomerController@index');//m_customer_all.html 客户管理
//反馈
Route::get('manage/problem', 'manage\ProblemController@index');//m_problem.html 反馈问题 - 列表 /该模块的首页
Route::get('manage/problem/reply/{id}', 'manage\ProblemController@reply');//m_problem.html 反馈问题 - 回复
Route::get('manage/problem/export', 'manage\ProblemController@export');//colleague.html 导出
Route::get('manage/problem/import_template', 'manage\ProblemController@import_template');// -导入模版
//同事
Route::get('manage/staff/list', 'manage\StaffController@list');//m_staff.html 我的同事--管理
Route::get('manage/staff', 'manage\StaffController@index');//colleague.html 我的同事--列表
Route::get('manage/staff/add/{id}', 'manage\StaffController@add');//m_staff_add.html 添加员工
Route::get('manage/staff/export', 'manage\StaffController@export');//colleague.html 我的同事--导出
Route::get('manage/staff/import_template', 'manage\StaffController@import_template');//colleague.html 我的同事--导入模版
Route::get('manage/staff/select', 'manage\StaffController@select');// 同事选择-弹窗
//工单
Route::get('manage/work', 'manage\WorkController@index');//m_work_monitor.html 工单管理
Route::get('manage/work/list', 'manage\WorkController@list');//Repair_list.html 我的工单
Route::get('manage/work/info/{id}', 'manage\WorkController@info');//work_add.html 工单--详情
Route::get('manage/work/export', 'manage\WorkController@export');//导出
//考次试卷
Route::get('manage/exam/add/{id}', 'manage\ExamController@add');//x_examination_add.html 试题添加
Route::get('manage/exam', 'manage\ExamController@index');//x_examination_list.html 试题管理
Route::get('manage/exam/info/{id}', 'manage\ExamController@info');//-详情
Route::get('manage/exam/export', 'manage\ExamController@export');//--导出
Route::get('manage/exam/info/{id}', 'manage\ExamController@info');//
Route::get('manage/exam/exportStaff', 'manage\ExamController@exportStaff');//--导出考试结果
//试题
Route::get('manage/subject', 'manage\SubjectController@index');//x_questions.html 试题管理-导入
Route::get('manage/subject/add/{id}', 'manage\SubjectController@add');//x_questions_add.html 试题添加
Route::get('manage/subject/info/{id}', 'manage\SubjectController@info');//-详情
Route::get('manage/subject/export', 'manage\SubjectController@export');//--导出
Route::get('manage/subject/import_template', 'manage\SubjectController@import_template');//--导入模版
Route::get('manage/subject/select', 'manage\SubjectController@select');//x_questions.html 试题选择-弹窗
// 试卷
Route::get('manage/paper', 'manage\PaperController@index');//x_testpaper_list.html 试卷列表
Route::get('manage/paper/add/{id}', 'manage\PaperController@add');//x_testpaper_add.html 试卷添加
Route::get('manage/paper/info/{id}', 'manage\PaperController@info');//-详情
Route::get('manage/paper/export', 'manage\PaperController@export');//--导出
Route::get('manage/paper/select', 'manage\PaperController@select');// 试卷选择-弹窗

//成线
Route::get('manage/exam_score', 'manage\ExamScoreController@index');//examin_cj.html 试题管理--成线列表


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//web-huawu 客服
Route::get('huawu/testUpfile', 'huawu\IndexController@testUpfile');// 测试
Route::get('huawu/test', 'huawu\IndexController@test');// 测试
Route::get('huawu', 'huawu\IndexController@index');//main.html 首页
Route::get('huawu/getHistoryIdTest', 'huawu\IndexController@getHistoryIdTest');// 测试：根据主表id，获得对应的历史表id
Route::get('huawu/{company_id}/login', 'huawu\IndexController@login');//login.html 登录
Route::get('huawu/login', 'huawu\IndexController@login');//login.html 登录
Route::get('huawu/{company_id}/logout', 'huawu\IndexController@logout');// 注销
Route::get('huawu/logout', 'huawu\IndexController@logout');// 注销
   Route::get('huawu/info', 'huawu\IndexController@info');//myinfo.html  个人信息
   Route::get('huawu/password', 'huawu\IndexController@password');//psdmodify.html 个人信息-修改密码
//客户
   Route::get('huawu/customer', 'huawu\CustomerController@index');//customer.html 我的客户
   Route::get('huawu/customer/day_count', 'huawu\CustomerController@dayCount');//achievement.html 我的客户
//同事
   Route::get('huawu/staff', 'huawu\StaffController@index');//colleague.html  我的同事
Route::get('huawu/staff/select', 'huawu\StaffController@select');// 同事选择-弹窗
//在线考试
Route::get('huawu/exam', 'huawu\ExamController@index');//examin_list.html 在线考试
Route::get('huawu/exam/doing/{id}', 'huawu\ExamController@doing');//examin_do.html 在线考试
Route::get('huawu/exam/win/{id}', 'huawu\ExamController@win');//examin_over.html 在线考试

//反馈
// Route::get('huawu/problem/add', 'huawu\ProblemController@add');//feedback.html 在线反馈

//反馈
#Route::get('huawu/problem/add', 'huawu\ProblemController@add');//feedback.html 在线反馈
Route::get('huawu/problem/add/{id}', 'huawu\ProblemController@add');//feedback.html 问题反馈-提交问题

Route::get('huawu/problem', 'huawu\ProblemController@index');//m_problem.html 反馈问题 - 列表 /该模块的首页
//Route::get('huawu/problem/reply/{id}', 'huawu\ProblemController@reply');//m_problem.html 反馈问题 - 回复
Route::get('huawu/problem/export', 'huawu\ProblemController@export');//colleague.html 导出
Route::get('huawu/problem/import_template', 'huawu\ProblemController@import_template');// -导入模版
//学习
Route::get('huawu/lore', 'huawu\LoreController@index');//know_list.html  在线学习-列表
Route::get('huawu/lore/info/{id}', 'huawu\LoreController@info');//know_view.html 在线学习-详情

//通知公告
Route::get('huawu/notice', 'huawu\NoticeController@index');//列表
Route::get('huawu/notice/info/{id}', 'huawu\NoticeController@info');//详情
//工单
Route::get('huawu/work', 'huawu\WorkController@index');//m_work_monitor.html 工单管理
Route::get('huawu/work/list', 'huawu\WorkController@list');///Repair_list.html 我的工单
Route::get('huawu/work/add/{id}', 'huawu\WorkController@add');//work_add.html 我的客户-增加工单
Route::get('huawu/work/history', 'huawu\WorkController@history');//work_history.html 工单-历史
Route::get('huawu/work/hot', 'huawu\WorkController@hot');//work_hot.html 工单- 热点
Route::get('huawu/work/re_list', 'huawu\WorkController@re_list');//work_Return.html  工单- 重新指派
Route::get('huawu/work/info/{id}', 'huawu\WorkController@info');//work_add.html 工单--详情
Route::get('huawu/work/reply/{id}', 'huawu\WorkController@reply');// 工单-- 回访


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-weixiu 维修
Route::get('weixiu', 'weixiu\IndexController@index');//main_wx.html  -首页
Route::get('weixiu/{company_id}/login', 'weixiu\IndexController@login');///login.html 登陆
Route::get('weixiu/login', 'weixiu\IndexController@login');///login.html 登陆
Route::get('weixiu/{company_id}/logout', 'weixiu\IndexController@logout');// 注销
Route::get('weixiu/logout', 'weixiu\IndexController@logout');// 注销
Route::get('weixiu/info', 'weixiu\IndexController@info');//myinfo.html 个人信息
Route::get('weixiu/password', 'weixiu\IndexController@password');//psdmodify.html- 修改密码
//客户
Route::get('weixiu/customer', 'weixiu\CustomerController@index');//customer.html  我的客户
Route::get('weixiu/customer/day_count', 'weixiu\CustomerController@dayCount');//achievement.html 我的业绩
//同事
Route::get('weixiu/staff', 'weixiu\StaffController@index');//colleague.html 我的同事
//在线考试
Route::get('weixiu/exam', 'weixiu\ExamController@index');//examin_list.html 在线考试
Route::get('weixiu/exam/doing/{id}', 'weixiu\ExamController@doing');//examin_do.html 在线考试
Route::get('weixiu/exam/win/{id}', 'weixiu\ExamController@win');//examin_over.html 在线考试

//反馈
#Route::get('weixiu/problem/add', 'weixiu\ProblemController@add');//feedback.html 在线反馈
Route::get('weixiu/problem/add/{id}', 'weixiu\ProblemController@add');//feedback.html 问题反馈-提交问题

Route::get('weixiu/problem', 'weixiu\ProblemController@index');//m_problem.html 反馈问题 - 列表 /该模块的首页
//Route::get('weixiu/problem/reply/{id}', 'weixiu\ProblemController@reply');//m_problem.html 反馈问题 - 回复
Route::get('weixiu/problem/export', 'weixiu\ProblemController@export');//colleague.html 导出
Route::get('weixiu/problem/import_template', 'weixiu\ProblemController@import_template');// -导入模版
//工单
Route::get('weixiu/work/list', 'weixiu\WorkController@list');///Repair_list.html  我的工单
Route::get('weixiu/work/info/{id}', 'weixiu\WorkController@info');//work_add.html 工单--详情
Route::get('weixiu/work/win/{id}', 'weixiu\WorkController@win');// 结单
//学习
Route::get('weixiu/lore', 'weixiu\LoreController@index');//know_list.html  在线学习-列表
Route::get('weixiu/lore/info/{id}', 'weixiu\LoreController@info');//know_view.html 在线学习-详情

//通知公告
Route::get('weixiu/notice', 'weixiu\NoticeController@index');//列表
Route::get('weixiu/notice/info/{id}', 'weixiu\NoticeController@info');//详情
//帮助中心
Route::get('weixiu/help', 'weixiu\HelpController@index');// 帮助中心

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::get('app/{company_id}/login', 'app\IndexController@login');//login.html 登录
Route::get('app/login', 'app\IndexController@login');//login.html 登录
Route::get('app/{company_id}/logout', 'app\IndexController@logout');// 注销
Route::get('app/logout', 'app\IndexController@logout');// 注销
Route::get('app/info', 'app\IndexController@info');//myinfo.html 我的详情
//客户
Route::get('app/customer', 'app\CustomerController@index');//customer_all.html 客户列表
//学习
Route::get('app/lore', 'app\LoreController@index');//study.html 学习列表
Route::get('app/lore/info/{id}', 'app\LoreController@info');//know_view.html 知识详情
//反馈
Route::get('app/problem/add', 'app\ProblemController@add');//problem.html 反馈问题
//工单
Route::get('app/work', 'app\WorkController@index');//work_monitor.html 工单


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//m
Route::get('m', 'm\IndexController@index');//index.html  首页
Route::get('m/index_back', 'm\IndexController@indexBack');// index-bk.html 首页
Route::get('m/{company_id}/login', 'm\IndexController@login');//login.html 员工登录
Route::get('m/login', 'm\IndexController@login');//login.html 员工登录
Route::get('m/{company_id}/logout', 'm\IndexController@logout');// 注销
Route::get('m/logout', 'm\IndexController@logout');// 注销
Route::get('m/password', 'm\IndexController@password');//login.html 修改密码

Route::get('m/staff', 'm\StaffController@index');//myindex.html 个人中心--个人主页
Route::get('m/staff/info', 'm\StaffController@info');//myinfo.html 我的帐号--帐号信息
Route::get('m/staff/list', 'm\StaffController@list');//mycolleague.html 我的同事--同事列表
//客户
Route::get('m/customer', 'm\CustomerController@index');//customer_all.html 我的客户-列表
//反馈
// Route::get('m/problem/add', 'm\ProblemController@add');//feedback.html 问题反馈-提交问题
Route::get('m/problem/add/{id}', 'm\ProblemController@add');//feedback.html 问题反馈-提交问题

Route::get('m/problem', 'm\ProblemController@index');//m_problem.html 反馈问题 - 列表 /该模块的首页
//Route::get('m/problem/reply/{id}', 'm\ProblemController@reply');//m_problem.html 反馈问题 - 回复
Route::get('m/problem/export', 'm\ProblemController@export');//colleague.html 导出
Route::get('m/problem/import_template', 'm\ProblemController@import_template');// -导入模版

//在线考试
Route::get('m/exam', 'm\ExamController@index');//kaoshi.html 在线考试 --考试列表
Route::get('m/exam_score', 'm\ExamController@score');//kaoshi-cj.html 考试成绩-成绩列表
Route::get('m/exam_search', 'm\ExamController@search');//kaoshi-cj-view.html  考试成绩-维修业务知识测评--成绩查询

Route::get('m/exam/doing/{id}', 'm\ExamController@doing');//examin_do.html 在线考试
Route::get('m/exam/win/{id}', 'm\ExamController@win');//examin_over.html 在线考试
//学习
Route::get('m/lore', 'm\LoreController@index');//study.html 学习中心--知识列表
Route::get('m/lore/info/{id}', 'm\LoreController@info');//study_view.html 学习中心-知识详情页

//通知公告
Route::get('m/notice', 'm\NoticeController@index');//列表
Route::get('m/notice/info/{id}', 'm\NoticeController@info');//详情
// 工单
Route::get('m/work/win/{id}', 'm\WorkController@win');// 结单
//帮助中心
Route::get('m/help', 'm\HelpController@index');// 帮助中心
