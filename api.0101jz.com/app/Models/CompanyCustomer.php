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
}
