<?php
// 考次的人员
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExamStaff extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_exam_staff';

    /**
     * 获取考次的人员对应的员工-一维
     */
    public function examStaffs()
    {
        return $this->belongsTo('App\Models\CompanyStaff', 'staff_id', 'id');
    }

    /**
     * 获取考次的人员对应的员工历史-一维
     */
    public function examStaffHistory()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'staff_history_id', 'id');
    }
}
