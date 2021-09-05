
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
    var admin_username = $('input[name=admin_username]').val();
    var judgeuser =judge_validate(1,'工号',admin_username,true,'length',3,20);
    if(judgeuser != ''){
        layer_alert(judgeuser,3,0);
        // err_alert('<font color="#000000">' + judgeuser + '</font>');
        return false;
    }
    var admin_password = $('input[name=admin_password]').val();
    var judgePassword = judge_validate(1,'密码',admin_password,true,'length',6,20);
    if(judgePassword != ''){
        layer_alert(judgePassword,3,0);
        //err_alert('<font color="#000000">' + judgePassword + '</font>');
        return false;
    }

    var system_id = $('select[name=system_id]').val();
    var judge_seled = judge_validate(1,'登录平台',system_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择登录平台",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }
    var login_url = '';
    var index_url = '';
    switch(system_id)
    {
        case '1'://管理平台
        case 1://管理平台
            login_url = LOGIN_ADMIN_URL;
            index_url = INDEX_ADMIN_URL;
            break;
        case '2'://主管平台
        case 2://主管平台
            login_url = LOGIN_MANAGE_URL;
            index_url = INDEX_MANAGE_URL;
            break;
        case '3'://客服平台
        case 3://客服平台
            login_url = LOGIN_HUAWU_URL;
            index_url = INDEX_HUAWU_URL;
            break;
        case '4'://售后平台
        case 4://售后平台
            login_url = LOGIN_WEIXIU_URL;
            index_url = INDEX_WEIXIU_URL;
            break;
        default:
            layer_alert("请选择登录平台",3,0);
            //err_alert('<font color="#000000">' + judge_seled + '</font>');
            return false;
            break;
    }
    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
    console.log(login_url);
    console.log(data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : login_url,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            if(!ret.apistatus){//失败
                SUBMIT_FORM = true;//标记为未提交过
                //alert('失败');
                layer_alert(ret.errorMsg,3,0);
                // err_alert('<font color="#000000">' + ret.errorMsg + '</font>');
            }else{//成功
                go(index_url);
                // var supplier_id = ret.result['supplier_id'];
                //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                //    SUPPLIER_ID_VAL = supplier_id;
                //    $('input[name="supplier_id"]').val(supplier_id);
                //}
                // save_success();
            }
            layer.close(layer_index);//手动关闭
        }
    });
    return false;
}