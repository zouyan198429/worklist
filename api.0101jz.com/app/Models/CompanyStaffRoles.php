<?php
// 员工角色[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaffRoles extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff_roles';

}
