<?php
// 反馈问题
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProblem extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_problem';

    /**
     * 获取问题的回复-二维
     */
    public function problemReply()
    {
        return $this->hasMany('App\Models\CompanyProblemReply', 'problem_id', 'id');
    }

    /**
     * 获取反馈问题对应的业务分类[第一级分类]--一维
     */
    public function problemTypeId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'work_type_id', 'id');
    }

    /**
     * 获取反馈问题对应的业务分类[第二级分类]--一维
     */
    public function problemBusinessId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'business_id', 'id');
    }

    /**
     * 获取反馈问题对应的地区--一维
     */
    public function problemCity()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'city_id', 'id');
    }

    /**
     * 获取反馈问题对应的街道--一维
     */
    public function problemArea()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'area_id', 'id');
    }

}
