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
            'getHistoryIdApi' => 'comp/getHistoryId',//根据主表id，获得对应的历史表id
            'firstOrCreateApi' => 'comp/firstOrCreate',// 查找记录,或创建新记录[没有找到]
            'updateOrCreateApi' => 'comp/updateOrCreate',//已存在则更新，否则创建新模型--持久化模型，所以无需调用 save()
            'compareHistoryOrUpdateVersionApi' => 'comp/compareHistoryOrUpdateVersion',// 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
        ],
        'apiPath' => [
            'workAddInit' => 'work/add_init',// 工单添加页初始数据
            'saveWork' => 'work/add_save',// 工单添加/修改
            'workReSend' => 'work/workReSend',// 工单重新指定
            'workSure' => 'work/workSure',// 确认工单
            'workWin' => 'work/workWin',// 结单
            'workReply' => 'work/workReply',// 回访
            'initMobileWork' => 'work/mobile_index',// 手机站首页初始化数据
            'saveProblem' => 'problem/add_save',// 反馈问题添加/修改
            'staffImport' => 'staff/bathImport',// 批量导入员工
        ],
    ];