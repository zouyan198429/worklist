
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
    var work_type_id = $('select[name=work_type_id]').val();
    if(!judge_validate(4,'问题类型一級',work_type_id,true,'positive_int','','')){
        return false;
    }

    // 验证信息
    var business_id = $('select[name=business_id]').val();
    if(!judge_validate(4,'问题类型二級',business_id,true,'positive_int','','')){
        return false;
    }
    var content = $('textarea[name=content]').val();
    if(!judge_validate(4,'反馈内容',content,true,'length',2,2500)){
        return false;
    }

    var call_number = $('input[name=call_number]').val();
    if(!judge_validate(4,'客戶电话',call_number,true,'mobile','','')){
        return false;
    }

    var city_id = $('select[name=city_id]').val();
    if(!judge_validate(4,'客户地址一级',city_id,true,'positive_int','','')){
        return false;
    }
    var area_id = $('select[name=area_id]').val();
    if(!judge_validate(4,'客户地址二级',area_id,true,'positive_int','','')){
        return false;
    }
    var address = $('input[name=address]').val();
    if(!judge_validate(4,'客户地址详情',address,true,'length',2,46)){
        return false;
    }
    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SAVE_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(ret.status == 'error'){//失败
                SUBMIT_FORM = true;//标记为未提交过
                //alert('失败');
                err_alert(ret.msg);
            }else{//成功
                err_alert(ret.msg);
                go(GO_URL);
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
/*
* 通过用户选择的一级问题类型的id获取二级问题类型的值
* 2018.9.13 liuxin
* */
function getTwoType() {
    var parent_id = $("select[name=work_type_id]").val();
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : TYPE_URL,
        'data' : {'id':parent_id},
        'dataType' : 'json',
        'success' : function(ret){
            var option = '<option value="">请选择</option>';
            if (ret){
            var length = ret.length;
            for(var i = 0; i<length;i++) {
                var item = ret[i];
                option  += "<option value='"+item['id']+"'>"+item['type_name']+"</option>"
            }
            }else{
                err_alert('该分类没有二级分类');
            }
            $('select[name=business_id]').empty();
            $('select[name=business_id]').append(option);
            layer.close(layer_index)//手动关闭

        }
    })
}
/*
 * 通过用户选择的一级地址的id获取二级地址的值
 * 2018.9.13 liuxin
 * */
function getAreaArr() {
    var parent_id = $("select[name=city_id]").val();
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : ADDRESS_URL,
        'data' : {'id':parent_id},
        'dataType' : 'json',
        'success' : function(ret){
            var option = '<option value="">请选择</option>';
            if (ret){
                var length = ret.length;
                for(var i = 0; i<length;i++) {
                    var item = ret[i];
                    option  += "<option value='"+item['id']+"'>"+item['area_name']+"</option>"
                }
            }else{
                err_alert('该分类没有二级分类');
            }
            $('select[name=area_id]').empty();
            $('select[name=area_id]').append(option);
            layer.close(layer_index)//手动关闭

        }
    })
}