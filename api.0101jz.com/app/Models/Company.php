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
    const MODULE_NO_STAFF = 16;// 我的同事
    const MODULE_NO_ARR = [
        self::MODULE_NO_LORE => '知识库',
        self::MODULE_NO_EXAM => '在线考试',
        self::MODULE_NO_PROBLEM => '反馈问题',
        self::MODULE_NO_WORK => '工单',
        self::MODULE_NO_STAFF => '我的同事',
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

    // 帐号来源类型1本系统维护；2第三方系统同步
    const ACCOUNT_TYPE_SELF = 1;// 本系统维护
    const ACCOUNT_TYPE_SYNC = 2;// 第三方系统同步
    const ACCOUNT_TYPE_ARR = [
        self::ACCOUNT_TYPE_SELF => '本系统维护',
        self::ACCOUNT_TYPE_SYNC => '第三方系统同步',
    ];

    // 性别0未知1男2女
    public static $sexArr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 公司状态;1新注册2试用客户4VIP 8VIP 将过期  16过期会员32过期试用
    public static $companyStatusArr = [
        '1' => '新注册',
        '2' => '试用客户',
        '4' => 'VIP',
        '8' => 'VIP 将过期',
        '16' => '过期会员',
        '32' => '过期试用',
    ];
    // 表里没有的字段
    protected $appends = ['module_no_text', 'open_status_text', 'sex_text', 'company_status_text', 'account_type_text'];

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
     * 获取用户性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return static::$sexArr[$this->sex] ?? '';
    }

    /**
     * 获取公司状态文字
     *
     * @return string
     */
    public function getCompanyStatusTextAttribute()
    {
        return static::$companyStatusArr[$this->company_status] ?? '';
    }

    /**
     * 获取帐号来源类型文字
     *
     * @return string
     */
    public function getAccountTypeTextAttribute()
    {
        return static::ACCOUNT_TYPE_ARR[$this->account_type] ?? '';
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
