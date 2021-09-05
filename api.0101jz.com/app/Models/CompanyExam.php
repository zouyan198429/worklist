<?php
// 考次
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExam extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_exam';

    // 状态1待考试2考试中3已考试4缺考
    protected $status_arr = [
        '1' => '待考试',
        '2' => '考试中',
        '3' => '已考试',
        '4' => '缺考',
    ];

    // 表里没有的字段
    protected $appends = ['status_text'];

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
     * 获取考试计划对应的试卷-一维
     */
    public function examPaper()
    {
        return $this->belongsTo('App\Models\CompanyPaper', 'paper_id', 'id');
    }

    /**
     * 获取考试计划对应的试卷历史-一维
     */
    public function examPaperHistory()
    {
        return $this->belongsTo('App\Models\CompanyPaperHistory', 'paper_history_id', 'id');
    }
}
