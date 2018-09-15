<?php
// 工单操作日志
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWorkLog extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work_log';

    /**
     * 获取工单派发记录对应的历史员工[添加者]--一维
     */
    public function workLogHistoryStaffCreate()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'operate_staff_history_id', 'id');
    }

}
