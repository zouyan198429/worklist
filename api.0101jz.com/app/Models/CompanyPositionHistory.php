<?php
// 职位历史[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPositionHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_position_history';

    /**
     * 获取历史记录对应的职位--一维
     */
    public function historyPosition()
    {
        return $this->belongsTo('App\Models\CompanyPosition', 'position_id', 'id');
    }
}
