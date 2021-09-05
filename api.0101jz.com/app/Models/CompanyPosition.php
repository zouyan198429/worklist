<?php
// 职位[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPosition extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_position';

    /**
     * 获取职位的员工-二维
     */
    public function positionStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'position_id', 'id');
    }

    /**
     * 获取职位的历史-二维
     */

    public function positionHistory()
    {
        return $this->hasMany('App\Models\CompanyPositionHistory', 'position_id', 'id');
    }

    // 多对多
    /**
     * 适用职位的知识[通过中间表company_lore_position 多对多]
     */

    public function positionLores()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyLore'
            , 'company_lore_position'
            , 'position_id'
            , 'lore_id'
        )->withPivot('company_id', 'operate_staff_id', 'operate_staff_history_id')
         ->withTimestamps();
    }
}
