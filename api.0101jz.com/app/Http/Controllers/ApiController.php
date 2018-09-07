<?php

namespace App\Http\Controllers;


use App\Services\Common;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $company_id = null;
    protected $pro_unit_id = null;

//    public function init()
//    {
//        parent::init();
//
//        // CORS 预检请求
//        if (Yii::$app->getRequest()->getIsOptions()) {
//            return okJson('Preflight');
//        }
//    }

    /**
     * 主参数判断
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function InitParams(Request $request)
    {
        return true;
    }


}
