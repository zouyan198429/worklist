<?php
// 客户历史表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCustomerHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_customer_history';

}
