<?php
// 工单
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkDoing extends CompanyWork
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_doing';

    /**
     * 工单的标簦[通过中间表company_work_tags 多对多]
     */
//    public function workTags()
//    {
//        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
//        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
//        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
//        return $this->belongsToMany(
//            'App\Models\CompanyServiceTags'
//            , 'company_work_tags'
//            , 'work_id'
//            , 'tag_id'
//        )->withPivot('id', 'company_id', 'tag_name', 'operate_staff_id', 'operate_staff_history_id')->withTimestamps();
//    }
}
