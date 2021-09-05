<?php
// 试题历史答案
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubjectHistoryAnswer extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_subject_history_answer';

}
