<?php

namespace App\Business;
use App\Services\Common;


/**
 *
 */
class BaseBusiness
{

    /**
     * 新加
     *
     * @param string $model_name 模块名称
     * @param array  $dataParams 新加的数据
     * @return object 对象
     * @author zouyan(305463219@qq.com)
     */
    public static function __create($model_name, $dataParams = [])
    {
        // 获得对象
        $modelObj = null;
        Common::getObjByModelName($model_name, $modelObj);
        return Common::create($modelObj, $dataParams);
    }

    /**
     * 获得指定条件的多条数据
     *
     * @param string $model_name 模块名称
     * @param int 选填 $pagesize 每页显示的数量 [默认10]
     * @param int 选填 $total 总记录数,优化方案：传<=0传重新获取总数[默认0];=-5:只统计条件记录数量，不返回数据
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return array 数据
    $listData = [
    'pageSize' => $pagesize,
    'page' => $page,
    'total' => $total,
    'totalPage' => ceil($total/$pagesize),
    'dataList' => $requestData,
    ];
     * @author zouyan(305463219@qq.com)
     */
    public static function __getDataLimit($model_name, $page = 1, $pagesize = 10, $total = 0, $queryParams = [], $relations = []){
        $modelObj = null;
        Common::getObjByModelName($model_name, $modelObj);
        // $page = 1;
        // $pagesize = 10;
        // $total = 10;
//        $queryParams = [
//            'where' => [
//                  ['id', '&' , '16=16'],
//                ['company_id', $company_id],
//                //['mobile', $keyword],
//                //['admin_type',self::$admin_type],
//            ],
////            'select' => [
////                'id','company_id','type_name','sort_num'
////                //,'operate_staff_id','operate_staff_history_id'
////                ,'created_at'
////            ],
//            // 'orderBy' => ['id'=>'desc'],
//        ];

        /*
        if ($group_id > 0) {
            array_push($queryParams['where'], ['group_id', $group_id]);
        }

        if (!empty($keyword)) {
            array_push($queryParams['where'], ['real_name', 'like', '%' . $keyword . '%']);
        }
        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
        if (!empty($ids)) {
            if (strpos($ids, ',') === false) { // 单条
                array_push($queryParams['where'], ['id', $ids]);
            } else {
                $queryParams['whereIn']['id'] = explode(',', $ids);
            }
        }
        */
        // $relations = ''; $requestData =
        return Common::getModelListDatas($modelObj, $page, $pagesize, $total, $queryParams, $relations);

    }
}