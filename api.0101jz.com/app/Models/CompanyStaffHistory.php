<?php
// 员工表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaffHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff_history';

    /**
     * 获取历史记录对应的员工--一维
     */
    public function historyStaff()
    {
        return $this->belongsTo('App\Models\CompanyStaff', 'staff_id', 'id');
    }
}
