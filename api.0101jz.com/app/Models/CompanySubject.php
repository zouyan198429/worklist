<?php
// 试题
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubject extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_subject';

    // 题目类型1单选；2多选；4判断
    protected $type_arr = [
        '1' => '单选',
        '2' => '多选',
        '4' => '判断',
    ];

    // 表里没有的字段
    protected $appends = ['type_text'];

    /**
     * 获取题目类型的状态文字
     *
     * @return string
     */
    public function getTypeTextAttribute()
    {
        return $this->type_arr[$this->subject_type] ?? '';
    }

    /**
     * 获取试题的答案-二维
     */

    public function subjectAnswer()
    {
        return $this->hasMany('App\Models\CompanySubjectAnswer', 'subject_id', 'id');
    }

    /**
     * 获取试题的历史-二维
     */

    public function subjectHistory()
    {
        return $this->hasMany('App\Models\CompanySubjectHistory', 'subject_id', 'id');
    }

    /**
     * 获取试题对应的试题分类-一维
     */
    public function answerType()
    {
        return $this->belongsTo('App\Models\CompanySubjectType', 'type_id', 'id');
    }
}
