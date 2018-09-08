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
Route::get('admin/index', 'admin\IndexController@index');//index.html  首页
Route::get('admin/login', 'admin\IndexController@login');//login.html 登录
Route::get('admin/logout', 'admin\IndexController@logout');// 注销
Route::get('admin/password', 'admin\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('admin/info', 'admin\IndexController@info');//myinfo.html 个人信息--显示

  //管理员
Route::get('admin/siteAdmin', 'admin\SiteAdminController@index');//class_admin.html  管理员管理

// 系统基本设置
Route::get('admin/workCallerType', 'admin\WorkCallerTypeController@index');//class_call.html 来电分类
Route::get('admin/area', 'admin\AreaController@index');//class_quyu.html  区域管理
Route::get('admin/tags', 'admin\TagsController@index');//class_tags.html  业务标签

// 角色/权限
Route::get('admin/roles', 'admin\RolesController@index');//class_jiaose.html 角色/权限管理//

// 部门
Route::get('admin/department', 'admin\DepartmentController@index');//class_bumen.html  部门管理

// 员工
Route::get('admin/staff', 'admin\StaffController@index');//Colleague.html 我的同事

//客户
Route::get('admin/customer/index', 'admin\CustomerController@index');//customer_all.html 客户管理
Route::get('admin/customer/dayCount', 'admin\CustomerController@dayCount');//achievement.html  我的客户-按日统计--*
Route::get('admin/customer_type/index', 'admin\CustomerTypeController@index');//class_kehu.html 客户分类

//工单
Route::get('admin/work_type/index', 'admin\WorkTypeController@index');//class_order.html 工单分类
Route::get('admin/work/index', 'admin\WorkController@index');//work_monitor.html 工单管理

//统计
Route::get('admin/count_call/index', 'admin\CountCallController@index');//count_call.html  来电统计//
Route::get('admin/count_customer/index', 'admin\CountCustomerController@index');//count_Customer.html  来电统计-客户//
Route::get('admin/count_repair/index', 'admin\CountRepairController@index');//count_Repair.html 来电统计-维修改数量

//知识
Route::get('admin/lore_type/index', 'admin\LoreTypeController@index');//know_class.html 知识分类
Route::get('admin/lore/index', 'admin\LoreController@index');//know_list.html 在线学习

//反馈
Route::get('admin/problem/index', 'admin\ProblemController@index');//problem.html 反馈问题
//考次试卷
Route::get('admin/exam/index', 'admin\ExamController@index');//x_examination_list.html 试次管理
Route::get('admin/exam/add', 'admin\ExamController@add');//x_examination_add.html  试次添加
//试题
Route::get('admin/subject/index', 'admin\SubjectController@index');//x_questions.html  试题管理
Route::get('admin/subject/add', 'admin\SubjectController@add');//x_questions_add.html  试题添加
Route::get('admin/subject_type/index', 'admin\SubjectTypeController@index');//x_questions_class.html 试题分类

// 试卷
Route::get('admin/paper/add', 'admin\PaperController@add');//x_testpaper_add.html 试题添加
Route::get('admin/paper/index', 'admin\PaperController@index');//x_testpaper_list.html 试卷列表

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
Route::get('manage/login', 'manage\IndexController@login');//login.html 登陆
Route::get('manage/logout', 'manage\IndexController@logout');// 注销
Route::get('manage/index', 'manage\IndexController@index');//main_admin.html -首页
Route::get('manage/index_hot', 'manage\IndexController@index_hot');//work_hot.html 首页
Route::get('manage/info', 'manage\IndexController@info');//myinfo.html 个人信息
Route::get('manage/password', 'manage\IndexController@password');//psdmodify.html  个人信息-修改密码

// 统计
Route::get('manage/count_call/index', 'manage\CountCallController@index');//count_call.html 来电统计 -来电
Route::get('manage/count_customer/index', 'manage\CountCustomerController@index');///count_Customer.html 来电统计--客户
Route::get('manage/count_repair/index', 'manage\CountRepairController@index');//count_Repair.html 来电统计
//学习
Route::get('manage/lore/add', 'manage\LoreController@add');//know_add.html 在线学习-添加
Route::get('manage/lore/index', 'manage\LoreController@index');//know_list.html  在线学习-列表
Route::get('manage/lore/list', 'manage\LoreController@list');////study.html 在线学习
Route::get('manage/lore/info', 'manage\LoreController@info');//know_view.html 在线学习-详情
//客户
Route::get('manage/customer/index', 'manage\CustomerController@index');//m_customer_all.html 客户管理
//反馈
Route::get('manage/problem/index', 'manage\ProblemController@index');//m_problem.html 反馈问题
//同事
Route::get('manage/staff/list', 'manage\StaffController@list');//m_staff.html 我的同事
Route::get('manage/staff/index', 'manage\StaffController@index');//colleague.html 我的同事
Route::get('manage/staff/add', 'manage\StaffController@add');//m_staff_add.html 添加员工
//工单
Route::get('manage/work/index', 'manage\WorkController@index');//m_work_monitor.html 工单管理
Route::get('manage/work/list', 'manage\WorkController@list');//Repair_list.html 我的工单
//考次试卷
Route::get('manage/exam/add', 'manage\ExamController@add');//x_examination_add.html 试题添加
Route::get('manage/exam/index', 'manage\ExamController@index');//x_examination_list.html 试题管理
//试题
Route::get('manage/subject/index', 'manage\SubjectController@index');//x_questions.html 试题管理-导入
Route::get('manage/subject/add', 'manage\SubjectController@add');//x_questions_add.html 试题添加
// 试卷
Route::get('manage/paper/add', 'manage\PaperController@add');//x_testpaper_add.html 试卷添加
Route::get('manage/paper/index', 'manage\PaperController@index');//x_testpaper_list.html 试卷列表
//成线
Route::get('manage/exam_score/index', 'manage\ExamScoreController@index');//examin_cj.html 试题管理--成线列表


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//web-huawu 客服
Route::get('huawu/login', 'huawu\IndexController@login');//login.html 登录
Route::get('huawu/logout', 'huawu\IndexController@logout');// 注销
Route::get('huawu/index', 'huawu\IndexController@index');//main.html 首页
   Route::get('huawu/info', 'huawu\IndexController@info');//myinfo.html  个人信息
   Route::get('huawu/password', 'huawu\IndexController@password');//psdmodify.html 个人信息-修改密码
//客户
   Route::get('huawu/customer/index', 'huawu\CustomerController@index');//customer.html 我的客户
   Route::get('huawu/customer/dayCount', 'huawu\CustomerController@dayCount');//achievement.html 我的客户
//同事
   Route::get('huawu/staff', 'huawu\StaffController@index');//colleague.html  我的同事
//在线考试
Route::get('huawu/exam/index', 'huawu\ExamController@index');//examin_list.html 在线考试
Route::get('huawu/exam/doing', 'huawu\ExamController@doing');//examin_do.html 在线考试
Route::get('huawu/exam/win', 'huawu\ExamController@win');//examin_over.html 在线考试
//反馈
Route::get('huawu/problem/add', 'huawu\ProblemController@add');//feedback.html 在线反馈
//学习
Route::get('huawu/lore/index', 'huawu\LoreController@index');//study.html 在线学习
Route::get('huawu/lore/info', 'huawu\LoreController@info');//know_view.html 在线学习
//工单
Route::get('huawu/work/index', 'huawu\WorkController@index');//m_work_monitor.html 工单管理
Route::get('huawu/work/list', 'huawu\WorkController@list');///Repair_list.html 我的工单///
Route::get('huawu/work/add', 'huawu\WorkController@add');//work_add.html 我的客户-增加工单
Route::get('huawu/work/history', 'huawu\WorkController@history');//work_history.html 工单-历史
Route::get('huawu/work/hot', 'huawu\WorkController@hot');//work_hot.html 工单- 热点
Route::get('huawu/work/re_list', 'huawu\WorkController@re_list');//work_Return.html  工单- 重新指派


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-weixiu 维修
Route::get('weixiu/login', 'weixiu\IndexController@login');///login.html 登陆
Route::get('weixiu/logout', 'weixiu\IndexController@logout');// 注销
Route::get('weixiu/index', 'weixiu\IndexController@index');//main_wx.html  -首页
Route::get('weixiu/info', 'weixiu\IndexController@info');//myinfo.html 个人信息
Route::get('weixiu/password', 'weixiu\IndexController@password');//psdmodify.html- 修改密码
//客户
Route::get('weixiu/customer/index', 'weixiu\CustomerController@index');//customer.html  我的客户
Route::get('weixiu/customer/dayCount', 'weixiu\CustomerController@dayCount');//achievement.html 我的业绩
//同事
Route::get('weixiu/staff/index', 'weixiu\StaffController@index');//colleague.html 我的同事
//在线考试
Route::get('weixiu/exam/doing', 'weixiu\ExamController@doing');//examin_do.html 在线考试
Route::get('weixiu/exam/index', 'weixiu\ExamController@index');//examin_list.html 在线考试
Route::get('weixiu/exam/win', 'weixiu\ExamController@win');//examin_over.html 在线考试
//反馈
Route::get('weixiu/problem/add', 'weixiu\ProblemController@add');//feedback.html 在线反馈
//工单
Route::get('weixiu/work/list', 'weixiu\WorkController@list');///Repair_list.html  我的工单
//学习
Route::get('weixiu/lore/index', 'weixiu\LoreController@index');//study.html 在线学习
Route::get('weixiu/lore/info', 'weixiu\LoreController@info');//know_view.html 在线学习

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::get('app/login', 'app\IndexController@login');//login.html 登录
Route::get('app/logout', 'app\IndexController@logout');// 注销
Route::get('app/info', 'app\IndexController@info');//myinfo.html 我的详情
//客户
Route::get('app/customer/index', 'app\CustomerController@index');//customer_all.html 客户列表
//学习
Route::get('app/lore/index', 'app\LoreController@index');//study.html 学习列表
Route::get('app/lore/info', 'app\LoreController@info');//know_view.html 知识详情
//反馈
Route::get('app/problem/add', 'app\ProblemController@add');//problem.html 反馈问题
//工单
Route::get('app/work/index', 'app\WorkController@index');//work_monitor.html 工单