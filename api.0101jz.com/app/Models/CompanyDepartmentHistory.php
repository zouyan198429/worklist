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

    /**
     * 获取历史记录对应的部门/小组--一维
     */
    public function historyDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'department_id', 'id');
    }
}
