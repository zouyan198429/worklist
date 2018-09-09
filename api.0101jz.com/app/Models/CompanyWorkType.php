<?php
// 工单类型[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_type';

    /**
     * 获取业务分类[第一级id]的反馈问题-二维
     */
    public function typeIdProblem()
    {
        return $this->hasMany('App\Models\CompanyProblem', 'work_type_id', 'id');
    }

    /**
     * 获取小业务分类[第二级id]的反馈问题-二维
     */
    public function businessIdProblem()
    {
        return $this->hasMany('App\Models\CompanyProblem', 'business_id', 'id');
    }

    /**
     * 获取业务分类[第一级id]的工单-二维
     */
    public function typeIdWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'work_type_id', 'id');
    }

    /**
     * 获取小业务分类[第二级id]的工单-二维
     */
    public function businessIdWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'business_id', 'id');
    }
}
