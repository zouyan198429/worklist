<?php
// 客户地区[二级分类]
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyArea extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'company_area';

    /**
     * 获取地区的客户-二维
     */
    public function cityCustomer()
    {
        return $this->hasMany('App\Models\CompanyCustomer', 'city_id', 'id');
    }

    /**
     * 获取街道的客户-二维
     */
    public function areaCustomer()
    {
        return $this->hasMany('App\Models\CompanyCustomer', 'area_id', 'id');
    }


    /**
     * 获取地区的反馈问题-二维
     */
    public function cityProblem()
    {
        return $this->hasMany('App\Models\CompanyProblem', 'city_id', 'id');
    }

    /**
     * 获取街道的反馈问题-二维
     */
    public function areaProblem()
    {
        return $this->hasMany('App\Models\CompanyProblem', 'area_id', 'id');
    }


    /**
     * 获取地区的工单-二维
     */
    public function cityWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'city_id', 'id');
    }

    /**
     * 获取街道的工单-二维
     */
    public function areaWork()
    {
        return $this->hasMany('App\Models\CompanyWork', 'area_id', 'id');
    }
}
