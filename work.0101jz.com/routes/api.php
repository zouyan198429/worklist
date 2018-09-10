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

//
// admin
// 登陆
Route::any('admin/ajax_login', 'admin\IndexController@ajax_login');// 登陆
// 客户分类
Route::any('admin/customer_type/ajax_alist', 'admin\CustomerTypeController@ajax_alist');//ajax获得列表数据
Route::any('admin/customer_type/ajax_del', 'admin\CustomerTypeController@ajax_del');// 删除
Route::any('admin/customer_type/ajax_save', 'admin\CustomerTypeController@ajax_save');// 新加/修改

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-manage
// 登陆
Route::any('manage/ajax_login', 'manage\IndexController@ajax_login');// 登陆
//同事
Route::any('manage/staff/ajax_alist', 'manage\StaffController@ajax_alist');//ajax获得列表数据
Route::any('manage/staff/ajax_del', 'manage\StaffController@ajax_del');// 删除

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//反馈问题 （liuxin）
Route::any('manage/problem/ajax_alist', 'manage\ProblemController@ajax_alist');//ajax获得反馈问题的列表数据
Route::any('manage/problem/ajax_del', 'manage\ProblemController@ajax_del');// 删除反馈问题
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-huawu 客服
Route::any('huawu/ajax_login', 'huawu\IndexController@ajax_login');// 登陆


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//web-weixiu 维修
// 登陆
Route::any('weixiu/ajax_login', 'weixiu\IndexController@ajax_login');// 登陆


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//app
Route::any('app/ajax_login', 'app\IndexController@ajax_login');// 登陆


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