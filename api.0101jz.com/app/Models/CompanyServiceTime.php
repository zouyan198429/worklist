<?php
// 业务时间[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyServiceTime extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_service_time';

    /**
     * 获取业务时间的工单-二维
     */
    public function serviceTimeWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'time_id', 'id');
    }
}
