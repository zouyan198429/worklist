
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
    $(document).on("change","input[name=subject_type]",function(){
        var subject_type = $(this).val();
        console.log('subject_type', subject_type);
        initAnswerList();// 重新格式化答案列表
        return false;
    });

    initAnswer(ANSWER_DATA_LIST, 1);// 初始化答案列表


});

//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证信息
    var id = $('input[name=id]').val();
    if(!judge_validate(4,'记录id',id,true,'digit','','')){
        return false;
    }

    var type_id = $('select[name=type_id]').val();
    var judge_seled = judge_validate(1,'考试分类',type_id,true,'digit','','');
    if(judge_seled != ''){
        layer_alert("请选择考试分类",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var subject_type = $('input[name=subject_type]:checked').val() || '';
    var judge_seled = judge_validate(1,'考试类型',subject_type,true,'custom',/^[1-4]$/,"");
    if(judge_seled != ''){
        layer_alert("请选择考试类型",3,0);
        //err_alert('<font color="#000000">' + judge_seled + '</font>');
        return false;
    }

    var title = $('textarea[name=title]').val();
    if(!judge_validate(4,'题目',title,true,'length',1,800)){
        return false;
    }

    // 判断题-
    if(subject_type == 4){
        var answer = $('input[name=answer]:checked').val() || '';
        var judge_seled = judge_validate(1,'答案',answer,true,'custom',/^[01]$/,"");
        if(judge_seled != ''){
            layer_alert("请选择答案",3,0);
            //err_alert('<font color="#000000">' + judge_seled + '</font>');
            return false;
        }

    }else{// 单选/多选
        var trsObj = $('#data_list').find('tr');
        if(trsObj.length <= 0){
            layer_alert("请先增加答案",3,0);
            return false;
        }
        var is_err = false;
        var has_answer = false;
        trsObj.each(function(){
            var trObj = $(this);
            var colum = trObj.find('.colum').html();
            var answer_content = trObj.find('input[name="answer_content[]"]').val();
            if(!judge_validate(4,'答案' + colum + '',answer_content,true,'length',1,500)){
                is_err = true;
                return false;
            }
            switch(subject_type)
            {
                case 1://1单选；
                case '1':
                    console.log('1单选');
                    if(trObj.find('input[name="answer_val"]').is(':checked')){
                        has_answer = true;
                    }
                    break;
                case 2://2多选
                case '2':
                    console.log('2多选');
                    if(trObj.find('input[name="check_answer_val[]"]').is(':checked')){
                        has_answer = true;
                    }
                    break;
                default:
                    console.log('其它' + subject_type);
                    break;
            }
        });
        if(is_err){
            return false;
        }
        // 没有选择答案
        if(!has_answer){
            layer_alert("请标记正确答案",3,0);
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
};

//业务逻辑部分
var otheraction = {
    add : function(){// 增加答案
        var data_list = {
            'data_list' : DEFAULT_DATA_LIST
        };
        initAnswer(data_list, 2);// 初始化答案列表
        return false;
    },
    del : function(obj){// 删除
        var recordObj = $(obj);
        var index_query = layer.confirm('确定移除当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var trObj = recordObj.closest('tr');
            trObj.remove();
            initAnswerList();// 重新格式化答案列表
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    moveUp : function(obj){// 上移
        var recordObj = $(obj);
        var current = recordObj.closest('tr');//获取当前<tr>
        var prev = current.prev();  //获取当前<tr>前一个元素
        console.log('index', current.index());
        if (current.index() > 0) {
            current.insertBefore(prev); //插入到当前<tr>前一个元素前
            initAnswerList();// 重新格式化答案列表
        }else{
            layer_alert("已经是第一个答案，不能移动了。",3,0);
        }
        return false;
    },
    moveDown : function(obj){// 下移
        var recordObj = $(obj);
        var current = recordObj.closest('tr');//获取当前<tr>
        var next = current.next(); //获取当前<tr>后面一个元素
        console.log('length', next.length);
        console.log('next', next);
        if (next.length > 0 && next) {
            current.insertAfter(next);  //插入到当前<tr>后面一个元素后面
            initAnswerList();// 重新格式化答案列表
        }else{
            layer_alert("已经是最后一个答案，不能移动了。",3,0);
        }
        return false;
    },
};
// 重新格式化答案列表
function initAnswerList(){
    var tbodyObj = $('#data_list');
    // 1单选；2多选；4判断
    var subject_type = $('input[name=subject_type]:checked').val() || '';
    console.log('subject_type', subject_type);
    if(subject_type == '4'){
        $('.answer_judge').show();
        $('.answer_many').hide();
    }else{
        $('.answer_judge').hide();
        $('.answer_many').show();
    }
    var key = 'A'.charCodeAt();
    console.log('key');
    var val = 1;
    tbodyObj.find('tr').each(function () {
        var trObj = $(this);
        var colum = String.fromCharCode(key);
        console.log('colum',colum );
        trObj.find('.colum').html(colum);
        trObj.find('input[name=answer_val]').val(val);
        trObj.find('.check_answer').val(val);
        switch(subject_type)
        {
            case 1://1单选；
            case '1':
                console.log('1单选');
                trObj.find('input[name=answer_val]').show();
                trObj.find('.check_answer').hide();
                break;
            case 2://2多选
            case '2':
                console.log('2多选');
                trObj.find('input[name=answer_val]').hide();
                trObj.find('.check_answer').show();
                break;
            default:
                console.log('其它' + subject_type);
                break;
        }
        key++;
        val *= 2;
    });
}
// 初始化答案列表
// data_list 数据对象 {'data_list':[{}]}
// type类型 1 全替换 2 追加到后面
function initAnswer(data_list, type){
    var htmlStr = resolve_baidu_template(DYNAMIC_BAIDU_TEMPLATE,data_list,'');//解析
    //alert(htmlStr);
    //alert(body_data_id);
    if(type == 1){
        $('#'+DYNAMIC_TABLE_BODY).html(htmlStr);
    }else{
        $('#'+DYNAMIC_TABLE_BODY).append(htmlStr);
    }
    initAnswerList();// 重新格式化答案列表
}

(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td>");
    document.write("            <input type=\"hidden\" name=\"answer_id[]\" value=\"<%=item.id%>\"\/>");
    document.write("            <span class=\"colum\"><\/span>、<input type=\"text\" name=\"answer_content[]\" class=\"inp wlong\" value=\"<%=item.answer_content%>\" placeholder=\"请输入答案\"\/>");
    document.write("        <\/td>");
    document.write("        <td align=\"center\">");
    document.write("            <input type=\"radio\" name=\"answer_val\" value=\"\"  <%if( item.is_right == 1){%>  checked=\"checked\"  <%}%> \/>");
    document.write("            <input type=\"checkbox\" class=\"check_answer\" name=\"check_answer_val[]\" value=\"\" <%if( item.is_right == 1){%>  checked=\"checked\"  <%}%>\/>");
    document.write("        <\/td>");
    document.write("        <td>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.moveUp(this)\">");
    document.write("                <i class=\"ace-icon fa fa-arrow-up bigger-60\"> 上移<\/i>");
    document.write("            <\/a>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.moveDown(this)\">");
    document.write("                <i class=\"ace-icon fa fa-arrow-down bigger-60\"> 下移<\/i>");
    document.write("            <\/a>");
    document.write("");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.del(this)\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60\"> 移除<\/i>");
    document.write("            <\/a>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();