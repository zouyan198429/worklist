<?php
// 工单来电类型[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkCallerType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_caller_type';

    /**
     * 获取来电类型的工单-二维
     */
    public function serviceTimeWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'caller_type_id', 'id');
    }
}
