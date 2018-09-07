<?php
// 工单操作日志
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkLog extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_log';

}
