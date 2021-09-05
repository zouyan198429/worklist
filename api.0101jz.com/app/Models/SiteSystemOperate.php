<?php
// 系统功能操作
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSystemOperate extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'site_system_operate';

    /**
     * 获取系统功能操作的企业功能-二维
     */
    public function sysCompanyOperate()
    {
        return $this->hasMany('App\Models\CompanySystemOperate', 'operate_id', 'id');
    }

    /**
     * 获取系统功能操作的角色权限-二维
     */
    public function sysOperateRolesPower()
    {
        return $this->hasMany('App\Models\CompanyRolesPower', 'operate_id', 'id');
    }

    /**
     * 获取功能对应的系统模块--一维
     */
    public function operateSysModel()
    {
        return $this->belongsTo('App\Models\SiteSystemModule', 'module_id', 'id');
    }


    /**
     * 获取功能对应的系统栏目-一维
     */
        public function operateSysColumn()
    {
        return $this->belongsTo('App\Models\SiteSystemModule', 'column_id', 'id');
    }

    /**
     * 获取功能的系统-一维
     */
    public function operateSys()
    {
        return $this->belongsTo('App\Models\SiteSystem', 'system_id', 'id');
    }
}
