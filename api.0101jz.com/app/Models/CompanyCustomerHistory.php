<?php
// 客户历史表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCustomerHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_customer_history';
    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 表里没有的字段
    protected $appends = ['sex_text'];

    /**
     * 获取性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return $this->sex_arr[$this->sex] ?? '';
    }
}
