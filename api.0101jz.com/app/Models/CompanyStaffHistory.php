<?php
// 员工表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaffHistory extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff_history';

    // 2超级管理员 1管理员 0客服
    protected $type_arr = [
        '0' => '客服',
        '1' => '管理员',
        '2' => '超级管理员',
    ];

    // 状态
    protected $account_status_arr = [
        '0' => '正常',
        '1' => '冻结',
    ];

    // 是否超级帐户
    protected $issuper_arr = [
        '0' => '普通帐户',
        '1' => '超级帐户',
    ];

    // 性别
    protected $sex_arr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    /**
     * 在数组中隐藏的属性
     *
     * @var array
     */
    protected $hidden = ['admin_password'];

    // 表里没有的字段
    protected $appends = ['account_statu_text', 'issuper_text', 'sex_text', 'type_text'];

    /**
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = $value;// md5($value);
    }

    /**
     * 获取用户的状态文字
     *
     * @return string
     */
    public function getTypeTextAttribute()
    {
        return $this->type_arr[$this->admin_type] ?? '';
    }

    /**
     * 获取用户的状态文字
     *
     * @return string
     */
    public function getAccountStatuTextAttribute()
    {
        return $this->account_status_arr[$this->account_status] ?? '';
    }

    /**
     * 获取用户的是否超级帐户文字
     *
     * @return string
     */
    public function getIssuperTextAttribute()
    {
        return $this->issuper_arr[$this->issuper] ?? '';
    }

    /**
     * 获取用户的性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return $this->sex_arr[$this->sex] ?? '';
    }

    /**
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAccountPasswordAttribute($value)
    {
        $this->attributes['account_password'] = $value;// md5($value);
    }

    /**
     * 获取员工的历史-二维
     */

    public function staffHistory()
    {
        return $this->hasMany('App\Models\CompanyStaffHistory', 'staff_id', 'id');
    }

    /**
     * 获取员工对应的部门--一维
     */
    public function staffDepartment()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'department_id', 'id');
    }

    /**
     * 获取员工对应的小组--一维
     */
    public function staffGroup()
    {
        return $this->belongsTo('App\Models\CompanyDepartment', 'group_id', 'id');
    }


    /**
     * 获取员工对应的职位- 一维
     */
    public function staffPosition()
    {
        return $this->belongsTo('App\Models\CompanyPosition', 'position_id', 'id');
    }

    /**
     * 获取历史记录对应的员工--一维
     */
    public function historyStaff()
    {
        return $this->belongsTo('App\Models\CompanyStaff', 'staff_id', 'id');
    }
}
