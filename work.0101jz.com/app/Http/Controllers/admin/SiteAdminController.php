<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class SiteAdminController extends LoginController
{
    /**
     * 列表页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        return view('admin.site_admin.index',[]);
    }


}
