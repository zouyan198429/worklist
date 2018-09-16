<?php
// 客户表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaffCustomer extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff_customer';

    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 表里没有的字段
    protected $appends = ['sex_text'];

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
     * 获取客户对应的客户主表 --一维
     */
    public function customerMainCustomer()
    {
        return $this->belongsTo('App\Models\CompanyCustomer', 'customer_id', 'id');
    }

    /**
     * 获取客户对应的员工主表 --一维
     */
    public function customerStaff()
    {
        return $this->belongsTo('App\Models\CompanyStaff', 'staff_id', 'id');
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
//    public function customerProblem()
//    {
//        return $this->hasMany('App\Models\CompanyProblem', 'customer_id', 'id');
//    }
}
