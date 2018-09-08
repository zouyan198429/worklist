<?php

namespace App\Http\Controllers\TinyWeb;

use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class WebBaseController extends ApiController
{
    // protected $company_id = null;
     protected $pro_unit_id = null;

    public function InitParams(Request $request)
    {
        $pro_unit_id = Common::getInt($request, 'pro_unit_id');

        Common::judgeInitParams($request, 'pro_unit_id', $pro_unit_id);

        $this->pro_unit_id = $pro_unit_id;
    }
}
