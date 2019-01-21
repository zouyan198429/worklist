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

    // 状态0新问题1已回复
    protected $status_arr = [
        '0' => '待回复',
        '1' => '已回复',
    ];

    // 表里没有的字段
    protected $appends = ['status_text'];

    /**
     * 获取试题顺序的文字
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->status_arr[$this->status] ?? '';
    }

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
        return $this->belongsTo('App\Models\CompanyProblemType', 'work_type_id', 'id');
    }

    /**
     * 获取反馈问题对应的业务分类[第二级分类]--一维
     */
    public function problemBusinessId()
    {
        return $this->belongsTo('App\Models\CompanyProblemType', 'business_id', 'id');
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

    /**
     * 获取反馈问题对应的客户 --一维
     */
    public function problemCustomer()
    {
        return $this->belongsTo('App\Models\CompanyCustomer', 'customer_id', 'id');
    }
    /**
     * 获取客户对应的类型 --一维
     */
    public function problemCustomerType()
    {
        return $this->belongsTo('App\Models\CompanyCustomerType', 'type_id', 'id');
    }

}
