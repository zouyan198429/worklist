<?php
// 部门记录历史
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDepartmentHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_department_history';

}
