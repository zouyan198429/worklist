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

    /**
     * 获取试题分类的试题-二维
     */
    public function typeSubject()
    {
        return $this->hasMany('App\Models\CompanySubject', 'type_id', 'id');
    }

    /**
     * 获取试题分类的试题历史-二维
     */
    public function typeSubjectHistory()
    {
        return $this->hasMany('App\Models\CompanySubjectHistory', 'type_id', 'id');
    }
}
