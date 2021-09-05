<?php
// 业务标签[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyServiceTags extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_service_tags';

    /**
     * 获取业务标签的工单-二维
     */
    public function tagWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'tag_id', 'id');
    }
    /**
     * 标簦的工单[通过中间表company_work_tags 多对多]
     */
    public function tagWorks()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyWork'
            , 'company_work_tags'
            , 'tag_id'
            , 'work_id'
        )->withPivot('id', 'company_id', 'tag_name', 'operate_staff_id', 'operate_staff_history_id')->withTimestamps();
    }
}
