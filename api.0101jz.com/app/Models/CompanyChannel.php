<?php
// 渠道[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyChannel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_channel';

    /**
     * 获取渠道对应的部门--一维
     */
    public function channelDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'department_id', 'id');
    }

    /**
     * 获取渠道对应的小组--一维
     */
    public function channelGroup()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'group_id', 'id');
    }

}
