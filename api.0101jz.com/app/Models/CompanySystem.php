<?php
// 企业系统
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySystem extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_system';

    /**
     * 获取企业系统对应的系统--一维
     */
    public function companySysSystem()
    {
        return $this->belongsTo('App\Models\SiteSystem', 'system_id', 'id');
    }

}
