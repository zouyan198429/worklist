<?php
// 员工管理渠道
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaffChannel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff_channel';

    /**
     * 获取员工管理渠道对应的部门--一维
     */
    public function staffChannelDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'department_id', 'id');
    }

    /**
     * 获取员工管理渠道对应的小组--一维
     */
    public function staffChannelGroup()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'group_id', 'id');
    }

    /**
     * 获取员工管理渠道对应的渠道--一维
     */
    public function staffChannelChannel()
    {
        return $this->belongsTo('App\Models\CompanyChannel', 'channel_id', 'id');
    }


    /**
     * 获取员工管理渠道对应的员工--一维
     */
    public function staffChannelStaff()
    {
        return $this->belongsTo('App\Models\CompanyStaff', 'staff_id', 'id');
    }

}
