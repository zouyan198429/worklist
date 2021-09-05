<?php
// 工单派发记录
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkSends extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_sends';

    /**
     * 获取工单派发记录对应的历史员工[添加者]--一维
     */
    public function workSendHistoryStaffCreate()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'operate_staff_history_id', 'id');
    }

    /**
     * 获取工单派发记录对应的历史员工[添加者]--一维
     */
    public function workSendHistoryStaffSend()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'send_staff_history_id', 'id');
    }
}
