<?php
// 员工部门职位表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDepartmentPosition extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_department_position';

}
