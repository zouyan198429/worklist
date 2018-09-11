<?php
// 系统[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSystem extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'site_system';

    /**
     * 获取系统的企业系统-二维
     */
    public function systemCompanySys()
    {
        return $this->hasMany('App\Models\CompanySystem', 'system_id', 'id');
    }

    /**
     * 获取系统的模块-二维
     */
    public function systemSysModel()
    {
        return $this->hasMany('App\Models\SiteSystemModule', 'system_id', 'id');
    }

    /**
     * 获取系统的功能-二维
     */
    public function systemOperate()
    {
        return $this->hasMany('App\Models\SiteSystemOperate', 'system_id', 'id');
    }

    /**
     * 获取系统的企业功能-二维
     */
    public function systemCompanyOperate()
    {
        return $this->hasMany('App\Models\CompanySystemOperate', 'system_id', 'id');
    }
}
