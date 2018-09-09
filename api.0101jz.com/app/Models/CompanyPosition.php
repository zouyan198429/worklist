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
}
