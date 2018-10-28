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

    // 状态1待考试2考试中3已考试4缺考
    protected $status_arr = [
        '1' => '待考试',
        '2' => '考试中',
        '3' => '已考试',
        '4' => '缺考',
    ];

    // 是否及格0待考1未及格2及格
    protected $pass_arr = [
        '0' => '待考试',
        '1' => '未及格',
        '2' => '及格',
    ];

    // 表里没有的字段
    protected $appends = ['status_text', 'pass_text'];

    /**
     * 获取状态的文字
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->status_arr[$this->status] ?? '';
    }

    /**
     * 获取状态的文字
     *
     * @return string
     */
    public function getPassTextAttribute()
    {
        return $this->pass_arr[$this->is_pass] ?? '';
    }

    /**
     * 获取考次的人员的答案-二维
     */
    public function examStaffSubject()
    {
        return $this->hasMany('App\Models\CompanyExamStaffSubject', 'exam_staff_id', 'id');
    }

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

    /**
     * 获取考次的人员对应的考试信息-一维
     */
    public function staffExam()
    {
        return $this->belongsTo('App\Models\CompanyExam', 'exam_id', 'id');
    }
}
