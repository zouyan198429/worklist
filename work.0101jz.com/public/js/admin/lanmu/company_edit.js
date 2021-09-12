
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
    });
    var id = $('input[name=id]').val();
    if(id > 0){
        $('.user').hide();
    }

    //执行一个laydate实例
    // 开始日期
    laydate.render({
        elem: '.company_vipend' //指定元素
        ,type: 'datetime'
        ,value: COMPANY_VIPEND// '2018-08-18' //必须遵循format参数设定的格式
        ,min: get_now_format()//'2017-1-1'
        //,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });
});

//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证信息
    var id = $('input[name=id]').val();
    if(!judge_validate(4,'记录id',id,true,'digit','','')){
        return false;
    }

    var company_name = $('input[name=company_name]').val();
    if(!judge_validate(4,'公司名称',company_name,true,'length',2,40)){
        return false;
    }

    if(!judge_list_checked('selModuleNos',2)) {//没有选中的
        layer_alert('请选择开通模块！',3,0);
        return false;
    }

    var send_work_department_id = $('select[name=send_work_department_id]').val();
    var judge_seled = judge_validate(1,'接线部门',send_work_department_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择接线部门",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var account_type = $('input[name=account_type]:checked').val() || '';
    var judge_seled = judge_validate(1,'帐号来源类型',account_type,true,'custom',/^[12]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择帐号来源类型",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var open_status = $('input[name=open_status]:checked').val() || '';
    var judge_seled = judge_validate(1,'开启状态',open_status,true,'custom',/^[124]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择开启状态",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var company_linkman = $('input[name=company_linkman]').val();
    if(!judge_validate(4,'联系人',company_linkman,true,'length',1,30)){
        return false;
    }

    var sex = $('input[name=sex]:checked').val() || '';
    var judge_seled = judge_validate(1,'性别',sex,true,'custom',/^[12]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择性别",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var company_mobile = $('input[name=company_mobile]').val();
    if(!judge_validate(4,'手机',company_mobile,true,'mobile','','')){
        return false;
    }

    var company_status = $('input[name=company_status]:checked').val() || '';
    var judge_seled = judge_validate(1,'公司状态',company_status,true,'custom',/^([1248]|16|32)$/,"");
    if(judge_seled != ''){
        layer_alert("请选择公司状态",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    // 到期时间
    var company_vipend = $('input[name=company_vipend]').val();
    if(!judge_validate(4,'到期时间',company_vipend,true,'date','','')){
        return false;
    }
    // var sort_num = $('input[name=sort_num]').val();
    // if(!judge_validate(4,'排序',sort_num,false,'digit','','')){
    //     return false;
    // }

    if(id <= 0){

        var admin_username = $('input[name=admin_username]').val();
        if(!judge_validate(4,'用户名',admin_username,true,'length',6,20)){
            return false;
        }
        var admin_password = $('input[name=admin_password]').val();
        var sure_password = $('input[name=sure_password]').val();
        // var admin_password = $('input[name=admin_password]').val();
        if(!judge_validate(4,'密码',admin_password,true,'length',6,20)){
            return false;
        }
        // var sure_password = $('input[name=sure_password]').val();
        if(!judge_validate(4,'确认密码',sure_password,true,'length',6,20)){
            return false;
        }

        if(admin_password !== sure_password){
            layer_alert('确认密码和密码不一致！',5,0);
            return false;
        }
    }
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
