<?php
// 管理员
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAdmin extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'site_admin';

    // 2超级管理员 1管理员 0客服
    protected $type_arr = [
        '0' => '客服',
        '1' => '管理员',
        '2' => '超级管理员',
    ];

    // 表里没有的字段
    protected $appends = ['type_text'];

    /**
     * 在数组中隐藏的属性
     *
     * @var array
     */
    protected $hidden = ['admin_password'];

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
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = md5($value);
    }
}
