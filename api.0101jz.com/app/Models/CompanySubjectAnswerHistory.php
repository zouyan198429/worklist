<?php
// 试题答案历史
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubjectAnswerHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_subject_answer_history';

    /**
     * 获取答案历史对应的答案--一维
     */
    public function historyAnswer()
    {
        return $this->belongsTo('App\Models\CompanySubjectAnswer', 'answer_id', 'id');
    }

    /**
     * 获取答案对应的试题--一维
     */
    public function answerSubject()
    {
        return $this->belongsTo('App\Models\CompanySubject', 'subject_id', 'id');
    }
}
