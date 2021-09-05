<?php
// 公司
namespace App\Models;

use App\Services\Tool;
use Illuminate\Database\Eloquent\Model;

class Company extends BaseModel
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company';

    // 开通模块编号【1知识库；2在线考试；4反馈问题；8工单】
    const MODULE_NO_LORE = 1;// 知识库
    const MODULE_NO_EXAM = 2;// 在线考试
    const MODULE_NO_PROBLEM = 4;// 反馈问题
    const MODULE_NO_WORK = 8;// 工单
    const MODULE_NO_ARR = [
        self::MODULE_NO_LORE => '知识库',
        self::MODULE_NO_EXAM => '在线考试',
        self::MODULE_NO_PROBLEM => '反馈问题',
        self::MODULE_NO_WORK => '工单',
    ];

    // 开通状态1开通；2关闭；4作废【过时关闭】；
    const OPEN_STATUS_OPEN = 1;// 开通
    const OPEN_STATUS_CLOSE = 2;// 关闭
    const OPEN_STATUS_CANCEL = 4;// 作废
    const OPEN_STATUS_ARR = [
        self::OPEN_STATUS_OPEN => '开通',
        self::OPEN_STATUS_CLOSE => '关闭',
        self::OPEN_STATUS_CANCEL => '作废',
    ];

    // 表里没有的字段
    protected $appends = ['module_no_text', 'open_status_text'];

    /**
     * 获取开通模块文字
     *
     * @return string
     */
    public function getModuleNoTextAttribute()
    {
        return Tool::getBitVals(static::MODULE_NO_ARR, $this->module_no, '、');
        // return static::MODULE_NO_ARR[$this->module_no] ?? '';
    }

    /**
     * 获取开通状态文字
     *
     * @return string
     */
    public function getOpenStatusTextAttribute()
    {
        return Tool::getBitVals(static::OPEN_STATUS_ARR, $this->open_status, '、');
        // return static::OPEN_STATUS_ARR[$this->open_status] ?? '';
    }

    /**
     * 获取公司的员工-二维
     */
    public function companyStaff()
    {
        return $this->hasMany('App\Models\CompanyStaff', 'company_id', 'id');
    }

    /**
     * 获取员工对应的接线部门--一维
     */
    public function companyDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'send_work_department_id', 'id');
    }
}
