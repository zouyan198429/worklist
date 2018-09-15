<?php
// 工单
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWork extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_work';

    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 状态 0新工单2待反馈工单[处理中];4待回访工单;8已完成工单
    protected $status_arr = [
        '0' => '新工单',
        '2' => '处理中',
        '4' => '待回访',
        '8' => '已完成',
    ];

    // 表里没有的字段
    protected $appends = ['status_text', 'sex_text'];

    /**
     * 获取状态文字
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->status_arr[$this->status] ?? '';
    }

    /**
     * 获取性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return $this->sex_arr[$this->sex] ?? '';
    }


    /**
     * 获取工单对应的业务分类[第一级分类]--一维
     */
    public function workTypeId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'work_type_id', 'id');
    }

    /**
     * 获取工单对应的业务分类[第二级分类]--一维
     */
    public function workBusinessId()
    {
        return $this->belongsTo('App\Models\CompanyWorkType', 'business_id', 'id');
    }


    /**
     * 获取工单对应的地区--一维
     */
    public function workCity()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'city_id', 'id');
    }

    /**
     * 获取工单对应的街道--一维
     */
    public function workArea()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'area_id', 'id');
    }

    /**
     * 获取工单对应的业务时间--一维
     */
    public function workServiceTime()
    {
        return $this->belongsTo('App\Models\CompanyServiceTime', 'time_id', 'id');
    }

    /**
     * 获取工单对应的来电类型--一维
     */
    public function workCallerType()
    {
        return $this->belongsTo('App\Models\CompanyWorkCallerType', 'caller_type_id', 'id');
    }

    /**
     * 获取工单对应的标签--一维
     */
    public function workTag()
    {
        return $this->belongsTo('App\Models\CompanyServiceTags', 'tag_id', 'id');
    }

    /**
     * 获取工单对应的历史员工[添加者]--一维
     */
    public function workHistoryStaffCreate()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'operate_staff_history_id', 'id');
    }

    /**
     * 获取工单对应的历史员工[添加者]--一维
     */
    public function workHistoryStaffSend()
    {
        return $this->belongsTo('App\Models\CompanyStaffHistory', 'send_staff_history_id', 'id');
    }

    /**
     * 获取工单对应的客户--一维
     */
    public function workCustomer()
    {
        return $this->belongsTo('App\Models\CompanyCustomer', 'customer_id', 'id');
    }

    /**
     * 获取工单的派发记录-二维
     */
    public function workSends()
    {
        return $this->hasMany('App\Models\CompanyWorkSends', 'work_id', 'id');
    }

    /**
     * 获取工单的操作日志-二维
     */
    public function workLogs()
    {
        return $this->hasMany('App\Models\CompanyWorkLog', 'work_id', 'id');
    }

    /**
     * 工单的标簦[通过中间表company_work_tags 多对多]
     */
    public function workTags()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyServiceTags'
            , 'company_work_tags'
            , 'work_id'
            , 'tag_id'
        )->withPivot('id', 'company_id', 'tag_name', 'operate_staff_id', 'operate_staff_history_id')->withTimestamps();
    }
}
