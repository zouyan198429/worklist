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

}
