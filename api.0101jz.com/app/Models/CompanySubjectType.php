<?php
// 试题分类[一级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubjectType extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_subject_type';

}
