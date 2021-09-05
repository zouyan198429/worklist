var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    //提交
    $(document).on("click","#submitBtn",function(){
        var index_query = layer.confirm('您确定提交结单吗？', {
           btn: ['确定','取消'] //按钮
        }, function(){
            ajax_form();
           layer.close(index_query);
        }, function(){
        });
        return false;
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



    var win_content = $('textarea[name=win_content]').val();
    if(!judge_validate(4,'结单内容说明',win_content,true,'length',2,2000)){
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
                layer.msg('提交成功！', {
                    icon: 1,
                    shade: 0.3,
                    time: 4000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    go(LIST_URL);
                    //do something
                });
                // go(LIST_URL);
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
