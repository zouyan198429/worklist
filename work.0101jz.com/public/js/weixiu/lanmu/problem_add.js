
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

    var type_name = $('input[name=type_name]').val();
    if(!judge_validate(4,'名称',type_name,true,'length',2,40)){
        return false;
    }

    var sort_num = $('input[name=sort_num]').val();
    if(!judge_validate(4,'排序',sort_num,false,'digit','','')){
        return false;
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
/*
* 通过用户选择的一级问题类型的id获取二级问题类型的值
* 2018.9.13 liuxin
* */
function getTwoType() {
    var parent_id = $("select[name=work_type_id]").val();
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
        }
    })
}
/*
 * 通过用户选择的一级地址的id获取二级地址的值
 * 2018.9.13 liuxin
 * */
function getAreaArr() {
    var parent_id = $("select[name=city_id]").val();
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
        }
    })
}