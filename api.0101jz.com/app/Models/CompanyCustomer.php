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

}
