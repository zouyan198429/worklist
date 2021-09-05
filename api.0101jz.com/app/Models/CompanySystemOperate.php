<?php
// 企业功能操作
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySystemOperate extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_system_operate';

    /**
     * 获取企业功能的系统功能操作-一维
     */
    public function operateSysOperate()
    {
        return $this->belongsTo('App\Models\SiteSystemOperate', 'operate_id', 'id');
    }

    /**
     * 获取企业功能的系统-一维
     */
    public function CompanyOperateSys()
    {
        return $this->belongsTo('App\Models\SiteSystem', 'system_id', 'id');
    }

}
