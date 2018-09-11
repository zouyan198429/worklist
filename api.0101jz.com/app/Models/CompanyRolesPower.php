<?php
// 角色权限
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRolesPower extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_roles_power';

    /**
     * 获取角色权限对应的统功能操作--一维
     */
    public function powerSysOperate()
    {
        return $this->belongsTo('App\Models\SiteSystemOperate', 'operate_id', 'id');
    }

    /**
     * 获取角色权限对应的角色--一维
     */
    public function powerSysRole()
    {
        return $this->belongsTo('App\Models\CompanyRoles', 'role_id', 'id');
    }

}
