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

    /**
     * 获取地区的街道[子对象]-二维
     */
    public function cityArea()
    {
        return $this->hasMany('App\Models\CompanyArea', 'area_parent_id', 'id');
    }


    /**
     * 获取街道对应的地区--一维
     */
    public function areaCity()
    {
        return $this->belongsTo('App\Models\CompanyArea', 'area_parent_id', 'id');
    }

}
