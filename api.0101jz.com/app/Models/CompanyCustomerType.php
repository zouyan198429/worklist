<?php
// 系统客户类型[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCustomerType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_customer_type';

    /**
     * 获取客户类型下的客户-二维
     */
    public function typeCustomer()
    {
        return $this->hasMany('App\Models\CompanyCustomer', 'type_id', 'id');
    }
}
