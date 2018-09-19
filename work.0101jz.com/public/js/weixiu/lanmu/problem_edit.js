
const REL_CHANGE = {
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
$(function(){
    //维修类型值变动
    $(document).on("change",'select[name=work_type_id]',function(){
        changeFirstSel(REL_CHANGE.work_type, $(this).val(), 0, true);
        return false;
    });
    //区县值变动
    // $(document).on("change",'select[name=city_id]',function(){
    //     changeFirstSel(REL_CHANGE.area_city, $(this).val(), 0, true);
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
    if(!judge_validate(4,'内容',content,true,'length',2,2000)){
        return false;
    }

    // var call_number = $('input[name=call_number]').val();
    // if(!judge_validate(4,'来电号码',call_number,true,'length',3,30)){
    //     return false;
    // }
    //
    // var city_id = $('select[name=city_id]').val();
    // var judge_seled = judge_validate(1,'区县',city_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择区县",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }
    //
    // var area_id = $('select[name=area_id]').val();
    // var judge_seled = judge_validate(1,'街道',area_id,true,'digit','','');
    // if(judge_seled != ''){
    //     layer_alert("请选择街道",3,0);
    //     //err_alert('<font color="#000000">' + judge_seled + '</font>');
    //     return false;
    // }
    //
    // var address = $('input[name=address]').val();
    // if(!judge_validate(4,'详细地址',address,true,'length',1,50)){
    //     return false;
    // }

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
