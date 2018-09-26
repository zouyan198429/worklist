<?php
// 反馈类型[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProblemType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_problem_type';

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


}
