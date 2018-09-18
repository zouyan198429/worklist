<?php
// 工单
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyWorkDoing extends CompanyWork
{
    protected static $model_name = 'CompanyWorkDoing';

}
