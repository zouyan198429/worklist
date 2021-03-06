<?php
// 客户表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCustomer extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_customer';

    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 标记
    protected $is_tab_arr = [
        '0' => '未标记',
        '1' => '已标记',
    ];

    // 表里没有的字段
    protected $appends = ['sex_text','is_tab_text'];

    /**
     * 获取性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return $this->sex_arr[$this->sex] ?? '';
    }

    /**
     * 获取标记文字
     *
     * @return string
     */
    public function getIsTabTextAttribute()
    {
        return $this->is_tab_arr[$this->is_tab] ?? '';
    }

    /**
     * 获取客户对应的类型 --一维
     */
    public function customerType()
    {
        return $this->belongsTo('App\Models\CompanyCustomerType', 'type_id', 'id');
    }

    /**
     * 获取客户对应的地区--一维
     */
    public function customerCity()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'city_id', 'id');
    }

    /**
     * 获取客户对应的街道--一维
     */
    public function customerArea()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'area_id', 'id');
    }

    /**
     * 获取客户的反馈-二维
     */
    public function customerProblem()
    {
        return $this->hasMany('App\Models\CompanyProblem', 'customer_id', 'id');
    }
}
