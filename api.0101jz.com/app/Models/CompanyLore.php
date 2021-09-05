<?php
// 知识
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLore extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_lore';

    // 推荐级别1-5星
    protected $level_num_arr = [
        '1' => '★',
        '2' => '★★',
        '3' => '★★★',
        '4' => '★★★★',
        '5' => '★★★★★',
    ];

    // 表里没有的字段
    protected $appends = ['level_num_text'];

    /**
     * 获取用户的状态文字
     *
     * @return string
     */
    public function getLevelNumTextAttribute()
    {
        return $this->level_num_arr[$this->level_num] ?? '';
    }

    // 多对多
    /**
     * 知识适用的职位[通过中间表company_lore_position 多对多]
     */
    public function lorePositions()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyPosition'
            , 'company_lore_position'
            , 'lore_id'
            , 'position_id'
        )->withPivot('company_id', 'operate_staff_id', 'operate_staff_history_id')
         ->withTimestamps();
    }

}
