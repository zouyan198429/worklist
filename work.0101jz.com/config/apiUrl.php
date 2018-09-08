<?php

return [
        'common' => [
            //'index' => 'comp/index',//首页
            'getAllApi' => 'comp/all',//企业帐号记录列表
            'getlistApi' => 'comp/list',//企业帐号记录列表-可分页
            'getinfoApi' => 'comp/info',//根据id获得详情
            'addnewApi' => 'comp/add',//添加记录列表
            'addnewBathApi' => 'comp/addBath',//批量新加-data只能返回成功true:失败:false
            'addnewBathByIdApi' => 'comp/addBathById',//批量新加-data返回成功的id数组
            'saveApi' => 'comp/save',//修改记录列表
            'saveSyncByIdApi' => 'comp/sync',//同步修改关系
            'saveByIdApi' => 'comp/saveById',//通过id修改接口
            'saveDecIncByQueryApi' => 'comp/saveDecIncByQuery',//自增自减接口,通过条件-data操作的行数
            'saveDecIncByArrApi' => 'comp/saveDecIncByArr',//批量自增自减接口,通过数组[二维]-data操作的行数数组
            'delApi' => 'comp/del',//删除记录
            'detachApi' => 'comp/detach',//移除关系
        ],
        'apiPath' => [

        ],
    ];