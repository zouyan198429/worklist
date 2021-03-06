
const REL_CHANGE = {
    'department':{// 部门二级分类
        'child_sel_name': 'send_group_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择班组" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': DEPARTMENT_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    },
    'staff_department':{// 部门组获得员工---二级分类
        'child_sel_name': 'send_staff_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择员工" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': GROUP_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'group_id',// ajax调用时传递的参数名
        'other_params':{'department_id':0},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    },
    'work_type':{// 维修类型二级分类
        'child_sel_name': 'business_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择业务" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': WORKTYPE_CHILD_URL ,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    },
    'area_city':{// 区县二级分类
        'child_sel_name': 'area_id',// 第二级下拉框的name
        'child_sel_txt': {'': "请选择街道" },// 第二级下拉框的{值:请选择文字名称}
        'change_ajax_url': AREA_CHILD_URL,// 获取下级的ajax地址
        'parent_param_name': 'parent_id',// ajax调用时传递的参数名
        'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
    }
};

window.onload = function() {
    initPic();
};
function initPic(){
    baguetteBox.run('.baguetteBoxOne');
    // baguetteBox.run('.baguetteBoxTwo');
}
$(function(){
    //执行一个laydate实例
    laydate.render({
        elem: '.form-date' //指定元素
        ,type: 'datetime'
        ,value: BOOK_TIME// '2018-08-18' //必须遵循format参数设定的格式
        ,min: get_now_format()//'2017-1-1'
        //,max: '2017-12-31'
        ,calendar: true//是否显示公历节日
    });

    //维修类型值变动
    $(document).on("change",'select[name=work_type_id]',function(){
        changeFirstSel(REL_CHANGE.work_type, $(this).val(), 0, true);
        return false;
    });
    //区县值变动
    $(document).on("change",'select[name=city_id]',function(){
        changeFirstSel(REL_CHANGE.area_city, $(this).val(), 0, true);
        return false;
    });
    // //部门值变动
    // $(document).on("change",'select[name=send_department_id]',function(){
    //     // 初始化员工下拉框
    //     initSelect('send_staff_id' ,{"": "请选择员工"});
    //     changeFirstSel(REL_CHANGE.department, $(this).val(), 0, true);
    //     return false;
    // });
    // //小组值变动
    // $(document).on("change",'select[name=send_group_id]',function(){
    //     var send_department_id = $('select[name=send_department_id]').val();
    //     var tem_config = REL_CHANGE.staff_department;
    //     tem_config.other_params = {'department_id':send_department_id};
    //     changeFirstSel(tem_config, $(this).val(), 0, true);
    //     return false;
    // });
});

var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    //提交
    $(document).on("click","#submitBtn",function(){
        //var index_query = layer.confirm('您确定提交保存吗？', {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        ajax_form();
        //    layer.close(index_query);
        // }, function(){
        //});
        return false;
    })

});

//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证信息
    var id = $('input[name=id]').val();
    if(!judge_validate(4,'记录id',id,true,'digit','','')){
        return false;
    }

    // 判断是否上传图片
    var uploader = $('#myUploader').data('zui.uploader');
    var files = uploader.getFiles();
    var filesCount = files.length;

    var imgObj = $('#myUploader').closest('.resourceBlock').find(".upload_img");

    // if( (!judge_list_checked(imgObj,3)) && filesCount <=0 ) {//没有选中的
    //     layer_alert('请选择要上传的图片！',3,0);
    //     return false;
    // }

    var call_number = $('input[name=call_number]').val();
    if(!judge_validate(4,'来电号码',call_number,true,'length',3,30)){
        return false;
    }

    var contact_number = $('input[name=contact_number]').val();
    if(!judge_validate(4,'联系电话',contact_number,false,'length',3,30)){
        return false;
    }

    var caller_type_id = $('select[name=caller_type_id]').val();
    var judge_seled = judge_validate(1,'投诉类型',caller_type_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择投诉类型",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var work_type_id = $('select[name=work_type_id]').val();
    var judge_seled = judge_validate(1,'业务类型',work_type_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择业务类型",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var business_id = $('select[name=business_id]').val();
    var judge_seled = judge_validate(1,'业务',business_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择业务",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var content = $('textarea[name=content]').val();
    if(!judge_validate(4,'内容',content,true,'length',2,3000)){
        return false;
    }

    // var tag_id = $('input[name=tag_id]:checked').val() || '';
    // var judge_seled = judge_validate(1,'标签',tag_id,true,'custom',/^[0-9]*$/,"");
    // if(judge_seled != ''){
    //     layer_alert("请选择标签",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    var time_id = $('input[name=time_id]:checked').val() || '';
    var judge_seled = judge_validate(1,'工单时长',time_id,true,'custom',/^[0-9]*$/,"");
    if(judge_seled != ''){
        layer_alert("请选择工单时长",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    // var book_time = $('input[name=book_time]').val();
    // if(!judge_validate(4,'预约处理时间',book_time,true,'date','','')){
    //     return false;
    // }

    // var customer_name = $('input[name=customer_name]').val();
    // if(!judge_validate(4,'客户姓名',customer_name,true,'length',1,50)){
    //     return false;
    // }

    // var sex = $('input[name=sex]:checked').val() || '';
    // var judge_seled = judge_validate(1,'性别',sex,true,'custom',/^[12]$/,"");
    // if(judge_seled != ''){
    //     layer_alert("请选择性别",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    // var type_id = $('input[name=type_id]:checked').val() || '';
    // var judge_seled = judge_validate(1,'客户类别',type_id,true,'custom',/^[0-9]*$/,"");
    // if(judge_seled != ''){
    //     layer_alert("请选择客户类别",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }



    var city_id = $('select[name=city_id]').val();
    var judge_seled = judge_validate(1,'区县',city_id,false,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择区县",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var area_id = $('select[name=area_id]').val();
    var judge_seled = judge_validate(1,'街道',area_id,false,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择街道",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var address = $('input[name=address]').val();
    if(!judge_validate(4,'详细地址',address,false,'length',1,50)){
        return false;
    }

    // var send_department_id = $('select[name=send_department_id]').val();
    // var judge_seled = judge_validate(1,'部门',send_department_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择部门",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }
    //
    // var send_group_id = $('select[name=send_group_id]').val();
    // var judge_seled = judge_validate(1,'部门',send_group_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择小组",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    // var send_staff_id = $('select[name=send_staff_id]').val();
    // var judge_seled = judge_validate(1,'派发到同事',send_staff_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择派发到同事",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }

    // 派发到同事
    var send_staff_id = $('input[name=send_staff_id]').val();
    var judge_seled = judge_validate(1,'派发到同事',send_staff_id,true,'positive_int','','');
    if(judge_seled != ''){
        layer_alert("请选择派发到同事",3,0);
        return false;
    }

    // 验证通过
    // 上传图片
    if(filesCount > 0){
        var layer_index = layer.load();
        uploader.start();
        var intervalId = setInterval(function(){
            var status = uploader.getState();
            console.log('获取上传队列状态代码',uploader.getState());
            if(status == 1){
                layer.close(layer_index)//手动关闭
                clearInterval(intervalId);
                ajax_save();
            }
        },1000);
    }else{
        ajax_save();
    }
}


// 验证通过后，ajax保存
function ajax_save(){
    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
    console.log(SAVE_URL);
    console.log(data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SAVE_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                SUBMIT_FORM = true;//标记为未提交过
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                go(LIST_URL);
                // var supplier_id = ret.result['supplier_id'];
                //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                //    SUPPLIER_ID_VAL = supplier_id;
                //    $('input[name="supplier_id"]').val(supplier_id);
                //}
                // save_success();
            }
            layer.close(layer_index)//手动关闭
        }
    });
    return false;
}

//业务逻辑部分
var otheraction = {
    selectSendStaff: function(obj){// 选择派送到同事
        var recordObj = $(obj);
        //获得表单各name的值
        var weburl = SELECT_SEND_STAFF_URL;
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = '选择派送到同事';//"查看供应商";
        console.log('weburl', weburl);
        layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    updateSendStaff : function(obj){// 派送到同事更新
        var recordObj = $(obj);
        var index_query = layer.confirm('确定更新当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var send_staff_id = $('input[name=send_staff_id]').val();
            addStaff(send_staff_id);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
};

// 获得选中的派送到同事id 数组
function getSelectedStaffIds(){
    var send_staff_ids = [];
    var send_staff_id = $('input[name=send_staff_id]').val();
    send_staff_ids.push(send_staff_id);
    console.log('send_staff_ids' , send_staff_ids);
    return send_staff_ids;
}

// 取消
// send_staff_id 派送到同事id
function removeStaff(send_staff_id){
    var seled_send_staff_id = $('input[name=send_staff_id]').val();
    if(send_staff_id == seled_send_staff_id){
        $('input[name=send_staff_id]').val('');
        $('input[name=send_staff_history_id]').val('');
        $('.send_real_name').html('');
        $('.update_send_staff').hide();
    }
}

// 增加
// send_staff_id 派送到同事id, 多个用,号分隔
function addStaff(send_staff_id){
    if(send_staff_id == '') return ;
    var data = {};
    data['id'] = send_staff_id;
    console.log('data', data);
    console.log('AJAX_SEND_STAFF_ADD_URL', AJAX_SEND_STAFF_ADD_URL);
    var layer_index = layer.load();
    $.ajax({
        'async': false,// true,//false:同步;true:异步
        'type' : 'POST',
        'url' : AJAX_SEND_STAFF_ADD_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var staff_info = ret.result;
                console.log('staff_info', staff_info);
                $('input[name=send_staff_id]').val(staff_info.staff_id);
                $('input[name=send_staff_history_id]').val(staff_info.staff_history_id);
                $('.send_real_name').html(staff_info.department_name + ' ' + staff_info.group_name + ' ' + staff_info.real_name);
                var now_staff = staff_info.now_staff;// 最新的试题 0没有变化 ;1 已经删除  2 试卷不同
                if(now_staff == 2 ){
                    $('.update_send_staff').show();
                }else{
                    $('.update_send_staff').hide();
                }
            }
            layer.close(layer_index)//手动关闭
        }
    });
}
