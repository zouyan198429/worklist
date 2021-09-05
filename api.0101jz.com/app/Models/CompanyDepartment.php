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
     * 获取部门的员工-二维
     */
    public function departmentStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'department_id', 'id');
    }

    /**
     * 获取小组的员工-二维
     */
    public function groupStaff()
    {
        return $this->hasMany('App\Models\CompanyDepartment', 'group_id', 'id');
    }

    /**
     * 获取部门/小组的历史-二维
     */
    public function departmentHistory()
    {
        return $this->hasMany('App\Models\CompanyDepartmentHistory', 'department_id', 'id');
    }

    /**
     * 获取部门的小组[子对象]-二维
     */
    public function departmentGroup()
    {
        return $this->hasMany('App\Models\CompanyDepartment', 'department_parent_id', 'id');
    }


    /**
     * 获取小组对应的小组--一维
     */
    public function groupDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'department_parent_id', 'id');
    }
}
