<?php
// 公司
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends BaseModel
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company';


    /**
     * 获取公司的员工-二维
     */
    public function companyStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'company_id', 'id');
    }
}
