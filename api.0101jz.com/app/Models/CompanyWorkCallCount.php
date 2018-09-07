<?php
// 工单来电统计
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkCallCount extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_call_count';

}
