<?php
// 系统模块[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSystemModule extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'site_system_module';

    /**
     * 获取系统模块对应的系统--一维
     */
    public function sysModelSystem()
    {
        return $this->belongsTo('App\Models\SiteSystem', 'system_id', 'id');
    }

    /**
     * 获取系统模块的功能-二维
     */
    public function sysModelOperate()
    {
        return $this->hasMany('App\Models\SiteSystemOperate', 'module_id', 'id');
    }

    /**
     * 获取系统栏目的功能-二维
     */
    public function sysColumnOperate()
    {
        return $this->hasMany('App\Models\SiteSystemOperate', 'column_id', 'id');
    }

    /**
     * 获取模块的栏目[子对象]-二维
     */
    public function modelColumn()
    {
        return $this->hasMany('App\Models\SiteSystemModule', 'module_parent_id', 'id');
    }


    /**
     * 获取栏目对应的模块--一维
     */
    public function columnModel()
    {
        return $this->belongsTo('App\Models\SiteSystemModule', 'module_parent_id', 'id');
    }
}
