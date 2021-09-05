<?php
// 试卷
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPaper extends BaseModel
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_paper';

    // 试题顺序0固定顺序1随机顺序
    protected $order_type_arr = [
        '0' => '固定顺序',
        '1' => '随机顺序',
    ];

    // 表里没有的字段
    protected $appends = ['order_type_text'];

    /**
     * 获取试题顺序的文字
     *
     * @return string
     */
    public function getOrderTypeTextAttribute()
    {
        return $this->order_type_arr[$this->subject_order_type] ?? '';
    }

    /**
     * 获取试卷的考试计划-二维
     */
    public function paperExam()
    {
        return $this->hasMany('App\Models\CompanyExam', 'paper_id', 'id');
    }

}
