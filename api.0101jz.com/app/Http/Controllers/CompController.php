<?php

namespace App\Http\Controllers;

use App\Services\Common;
use Illuminate\Http\Request;

class CompController extends ApiController
{
     protected $company_id = null;
    // protected $pro_unit_id = null;

    public function InitParams(Request $request)
    {
        $not_log = Common::getInt($request, 'not_log');
        if($not_log != 1){
            $company_id = Common::getInt($request, 'company_id');

            Common::judgeInitParams($request, 'company_id', $company_id);
            $this->company_id = $company_id;
        }
    }
}
