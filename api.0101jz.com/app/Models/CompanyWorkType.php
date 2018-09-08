<?php
// 工单类型[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_type';

}
