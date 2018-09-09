<?php
// 部门[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_department';

    /**
     * 获取部门的员工
     */
    public function departmentStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'department_id', 'id');
    }

    /**
     * 获取小组的员工
     */
    public function groupStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'group_id', 'id');
    }

}
