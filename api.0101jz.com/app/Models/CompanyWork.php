<?php
// 工单
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWork extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work';

    /**
     * 获取工单对应的业务分类[第一级分类]--一维
     */
    public function workTypeId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'work_type_id', 'id');
    }

    /**
     * 获取工单对应的业务分类[第二级分类]--一维
     */
    public function workBusinessId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'business_id', 'id');
    }


    /**
     * 获取工单对应的地区--一维
     */
    public function workCity()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'city_id', 'id');
    }

    /**
     * 获取工单对应的街道--一维
     */
    public function workArea()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'area_id', 'id');
    }
}
