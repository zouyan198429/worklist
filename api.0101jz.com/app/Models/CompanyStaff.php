<?php
// 员工表
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStaff extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_staff';

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
    protected $appends = ['account_statu_text', 'issuper_text', 'sex_text'];

    /**
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = md5($value);
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
        $this->attributes['account_password'] = md5($value);
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
     * 员工的角色[通过中间表company_staff_roles 多对多]
     */
    public function staffRoles()
    {
        // return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        return $this->belongsToMany(
            'App\Models\CompanyRoles'
            , 'company_staff_roles'
            , 'staff_id'
            , 'role_id'
        )->withPivot('id', 'company_id', 'operate_staff_id', 'operate_staff_history_id')->withTimestamps();
    }


}
