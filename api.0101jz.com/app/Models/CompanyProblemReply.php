<?php
// 反馈问题回复
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProblemReply extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_problem_reply';

    /**
     * 获取问题回复对应的反馈问题--一维
     */
    public function replyProblem()
    {
        return $this->belongsTo('App\Models\CompanyProblem', 'problem_id', 'id');
    }
}
