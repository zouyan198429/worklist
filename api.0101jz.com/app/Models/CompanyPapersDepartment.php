<?php
// 试卷适合的员工部门职位
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPapersDepartment extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_papers_department';

}
