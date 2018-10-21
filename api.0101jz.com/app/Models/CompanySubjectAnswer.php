<?php
// 试题答案
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubjectAnswer extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_subject_answer';

    /**
     * 获取答案对应的试题--一维
     */
    public function answerSubject()
    {
        return $this->belongsTo('App\Models\CompanySubject', 'subject_id', 'id');
    }

    /**
     * 获取答案的历史-二维
     */

    public function answerHistory()
    {
        return $this->hasMany('App\Models\CompanySubjectAnswerHistory', 'answer_id', 'id');
    }
}
