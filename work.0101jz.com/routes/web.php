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
//Route::get('/test2', 'IndexController@test2');// 测试
Route::get('/', 'IndexController@index');// 首页
//Route::get('reg', 'IndexController@reg');// 注册
//Route::get('login', 'IndexController@login');// 登陆
//Route::get('logout', 'IndexController@logout');// 注销
//Route::get('404', 'IndexController@err404');// 404错误



// admin
Route::get('admin', 'admin\IndexController@index');//index.html  首页
Route::get('admin/login', 'admin\IndexController@login');//login.html 登录
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

//客户
Route::get('admin/customer', 'admin\CustomerController@index');//customer_all.html 客户管理
Route::get('admin/customer/day_count', 'admin\CustomerController@dayCount');//achievement.html  我的客户-按日统计--*
//客户类型
Route::get('admin/customer_type', 'admin\CustomerTypeController@index');//class_kehu.html 客户分类
Route::get('admin/customer_type/add/{id}', 'admin\CustomerTypeController@add');// 客户分类--添加

//工单
// 工单分类
Route::get('admin/work_type', 'admin\WorkTypeController@index');//class_order.html 工单分类
Route::get('admin/work_type/add/{id}', 'admin\WorkTypeController@add');// 工单分类--添加

Route::get('admin/work', 'admin\WorkController@index');//work_monitor.html 工单管理
Route::get('admin/work/info/{id}', 'admin\WorkController@info');//work_add.html 工单--详情

//统计
Route::get('admin/count_call', 'admin\CountCallController@index');//count_call.html  来电统计//
Route::get('admin/count_customer', 'admin\CountCustomerController@index');//count_Customer.html  来电统计-客户//
Route::get('admin/count_repair', 'admin\CountRepairController@index');//count_Repair.html 来电统计-维修改数量

//知识
// 知识分类
Route::get('admin/lore_type', 'admin\LoreTypeController@index');//know_class.html 知识分类
Route::get('admin/lore_type/add/{id}', 'admin\LoreTypeController@add');// 知识分类--添加

Route::get('admin/lore', 'admin\LoreController@index');//know_list.html 在线学习

//反馈
Route::get('admin/problem', 'admin\ProblemController@index');//problem.html 反馈问题
Route::get('admin/problem/reply/{id}', 'admin\ProblemController@reply');//m_problem.html 反馈问题 - 回复

//考次试卷
Route::get('admin/exam', 'admin\ExamController@index');//x_examination_list.html 试次管理
Route::get('admin/exam/add', 'admin\ExamController@add');//x_examination_add.html  试次添加
//试题
Route::get('admin/subject', 'admin\SubjectController@index');//x_questions.html  试题管理
Route::get('admin/subject/add', 'admin\SubjectController@add');//x_questions_add.html  试题添加
// 试题分类
Route::get('admin/subject_type', 'admin\SubjectTypeController@index');//x_questions_class.html 试题分类
Route::get('admin/subject_type/add/{id}', 'admin\SubjectTypeController@add');// 试题分类--添加

// 试卷
Route::get('admin/paper/add', 'admin\PaperController@add');//x_testpaper_add.html 试题添加
Route::get('admin/paper', 'admin\PaperController@index');//x_testpaper_list.html 试卷列表

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
Route::get('manage', 'manage\IndexController@index');//main_admin.html -首页
Route::get('manage/login', 'manage\IndexController@login');//login.html 登陆
Route::get('manage/logout', 'manage\IndexController@logout');// 注销
Route::get('manage/hot', 'manage\IndexController@index_hot');//work_hot.html 首页
Route::get('manage/info', 'manage\IndexController@info');//myinfo.html 个人信息
Route::get('manage/password', 'manage\IndexController@password');//psdmodify.html  个人信息-修改密码

// 统计
Route::get('manage/count_call', 'manage\CountCallController@index');//count_call.html 来电统计 -来电
Route::get('manage/count_customer', 'manage\CountCustomerController@index');///count_Customer.html 来电统计--客户
Route::get('manage/count_repair', 'manage\CountRepairController@index');//count_Repair.html 来电统计
//学习
Route::get('manage/lore/add', 'manage\LoreController@add');//know_add.html 在线学习-添加
Route::get('manage/lore', 'manage\LoreController@index');//know_list.html  在线学习-列表
Route::get('manage/lore/list', 'manage\LoreController@list');////study.html 在线学习
Route::get('manage/lore/info', 'manage\LoreController@info');//know_view.html 在线学习-详情
//客户
Route::get('manage/customer', 'manage\CustomerController@index');//m_customer_all.html 客户管理
//反馈
Route::get('manage/problem', 'manage\ProblemController@index');//m_problem.html 反馈问题 - 列表 /该模块的首页
Route::get('manage/problem/reply/{id}', 'manage\ProblemController@reply');//m_problem.html 反馈问题 - 回复
//同事
Route::get('manage/staff/list', 'manage\StaffController@list');//m_staff.html 我的同事--管理
Route::get('manage/staff', 'manage\StaffController@index');//colleague.html 我的同事--列表
Route::get('manage/staff/add/{id}', 'manage\StaffController@add');//m_staff_add.html 添加员工
//工单
Route::get('manage/work', 'manage\WorkController@index');//m_work_monitor.html 工单管理
Route::get('manage/work/list', 'manage\WorkController@list');//Repair_list.html 我的工单
Route::get('manage/work/info/{id}', 'manage\WorkController@info');//work_add.html 工单--详情
//考次试卷
Route::get('manage/exam/add', 'manage\ExamController@add');//x_examination_add.html 试题添加
Route::get('manage/exam', 'manage\ExamController@index');//x_examination_list.html 试题管理
//试题
Route::get('manage/subject', 'manage\SubjectController@index');//x_questions.html 试题管理-导入
Route::get('manage/subject/add', 'manage\SubjectController@add');//x_questions_add.html 试题添加
// 试卷
Route::get('manage/paper/add', 'manage\PaperController@add');//x_testpaper_add.html 试卷添加
Route::get('manage/paper', 'manage\PaperController@index');//x_testpaper_list.html 试卷列表
//成线
Route::get('manage/exam_score', 'manage\ExamScoreController@index');//examin_cj.html 试题管理--成线列表


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//web-huawu 客服
Route::get('huawu', 'huawu\IndexController@index');//main.html 首页
Route::get('huawu/getHistoryIdTest', 'huawu\IndexController@getHistoryIdTest');// 测试：根据主表id，获得对应的历史表id
Route::get('huawu/login', 'huawu\IndexController@login');//login.html 登录
Route::get('huawu/logout', 'huawu\IndexController@logout');// 注销
   Route::get('huawu/info', 'huawu\IndexController@info');//myinfo.html  个人信息
   Route::get('huawu/password', 'huawu\IndexController@password');//psdmodify.html 个人信息-修改密码
//客户
   Route::get('huawu/customer', 'huawu\CustomerController@index');//customer.html 我的客户
   Route::get('huawu/customer/day_count', 'huawu\CustomerController@dayCount');//achievement.html 我的客户
//同事
   Route::get('huawu/staff', 'huawu\StaffController@index');//colleague.html  我的同事
//在线考试
Route::get('huawu/exam', 'huawu\ExamController@index');//examin_list.html 在线考试
Route::get('huawu/exam/doing', 'huawu\ExamController@doing');//examin_do.html 在线考试
Route::get('huawu/exam/win', 'huawu\ExamController@win');//examin_over.html 在线考试
//反馈
Route::get('huawu/problem/add', 'huawu\ProblemController@add');//feedback.html 在线反馈
//学习
Route::get('huawu/lore', 'huawu\LoreController@index');//study.html 在线学习
Route::get('huawu/lore/info', 'huawu\LoreController@info');//know_view.html 在线学习
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
Route::get('weixiu/login', 'weixiu\IndexController@login');///login.html 登陆
Route::get('weixiu/logout', 'weixiu\IndexController@logout');// 注销
Route::get('weixiu/info', 'weixiu\IndexController@info');//myinfo.html 个人信息
Route::get('weixiu/password', 'weixiu\IndexController@password');//psdmodify.html- 修改密码
//客户
Route::get('weixiu/customer', 'weixiu\CustomerController@index');//customer.html  我的客户
Route::get('weixiu/customer/day_count', 'weixiu\CustomerController@dayCount');//achievement.html 我的业绩
//同事
Route::get('weixiu/staff', 'weixiu\StaffController@index');//colleague.html 我的同事
//在线考试
Route::get('weixiu/exam/doing', 'weixiu\ExamController@doing');//examin_do.html 在线考试
Route::get('weixiu/exam', 'weixiu\ExamController@index');//examin_list.html 在线考试
Route::get('weixiu/exam/win', 'weixiu\ExamController@win');//examin_over.html 在线考试
//反馈
#Route::get('weixiu/problem/add', 'weixiu\ProblemController@add');//feedback.html 在线反馈
Route::get('weixiu/problem/add/{id}', 'weixiu\ProblemController@add');//feedback.html 问题反馈-提交问题
//工单
Route::get('weixiu/work/list', 'weixiu\WorkController@list');///Repair_list.html  我的工单
Route::get('weixiu/work/info/{id}', 'weixiu\WorkController@info');//work_add.html 工单--详情
//学习
Route::get('weixiu/lore', 'weixiu\LoreController@index');//study.html 在线学习
Route::get('weixiu/lore/info', 'weixiu\LoreController@info');//know_view.html 在线学习

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::get('app/login', 'app\IndexController@login');//login.html 登录
Route::get('app/logout', 'app\IndexController@logout');// 注销
Route::get('app/info', 'app\IndexController@info');//myinfo.html 我的详情
//客户
Route::get('app/customer', 'app\CustomerController@index');//customer_all.html 客户列表
//学习
Route::get('app/lore', 'app\LoreController@index');//study.html 学习列表
Route::get('app/lore/info', 'app\LoreController@info');//know_view.html 知识详情
//反馈
Route::get('app/problem/add', 'app\ProblemController@add');//problem.html 反馈问题
//工单
Route::get('app/work', 'app\WorkController@index');//work_monitor.html 工单


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//m
Route::get('m', 'm\IndexController@index');//index.html  首页
Route::get('m/index_back', 'm\IndexController@indexBack');// index-bk.html 首页
Route::get('m/login', 'm\IndexController@login');//login.html 员工登录
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

Route::get('m/exam', 'm\ExamController@index');//kaoshi.html 在线考试 --考试列表
Route::get('m/exam_score', 'm\ExamController@score');//kaoshi-cj.html 考试成绩-成绩列表
Route::get('m/exam_search', 'm\ExamController@search');//kaoshi-cj-view.html  考试成绩-维修业务知识测评--成绩查询
Route::get('m/lore', 'm\LoreController@index');//study.html 学习中心--知识列表
Route::get('m/lore/info', 'm\LoreController@info');//study_view.html 学习中心-知识详情页
// 工单
Route::get('m/work/win/{id}', 'm\WorkController@win');// 结单