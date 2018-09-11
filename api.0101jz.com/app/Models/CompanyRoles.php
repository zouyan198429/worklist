<?php
// 角色[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRoles extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_roles';

    /**
     * 获取角色的角色权限-二维
     */
    public function rolesPower()
    {
        return $this->hasMany('App\Models\CompanyRolesPower', 'role_id', 'id');
    }


    /**
     * 角色的员工[通过中间表company_staff_roles 多对多]
     */
    public function roleStaffs()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyStaff'
            , 'company_staff_roles'
            , 'role_id'
            , 'staff_id'
        )->withPivot('id', 'company_id', 'operate_staff_id', 'operate_staff_history_id')->withTimestamps();
    }


}
