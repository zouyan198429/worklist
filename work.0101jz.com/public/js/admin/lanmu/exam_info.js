
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    initStaff(ID_VAL);// 页面初始化员工
});

//业务逻辑部分
var otheraction = {
    batchExportExcel:function(obj) {// 导出EXCEL-按条件
        var recordObj = $(obj);
        var index_query = layer.confirm('确定导出当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //获得搜索表单的值
            //append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
            //获得表单各name的值
            //var data = get_frm_values(SURE_FRM_IDS);// {}
            var data ={};
            data['exam_id'] = ID_VAL;
            data['is_export'] = 1;
            console.log(EXPORT_EXCEL_URL);
            console.log(data);
            var url_params = get_url_param(data);
            var url = EXPORT_EXCEL_URL + '?' + url_params;
            console.log(url);
            go(url);
            // go(EXPORT_EXCEL_URL);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
};
// 页面初始化员工
// id 考试id
function initStaff(id) {
    if(id <= 0) return ;
    var data = {};
    data['exam_id'] = id;
    var layer_index = layer.load();
    console.log(data);
    console.log(AJAX_STAFF_URL);
    $.ajax({
        'type' : 'POST',
        'url' : AJAX_STAFF_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var staff_list = ret.result;
                console.log('staff_list', staff_list);
                // 解析数据
                initAnswer('staff_td', staff_list, 1);
            }
            layer.close(layer_index)//手动关闭
        }
    });
}


// 初始化答案列表
// data_list 数据对象 {'data_list':[{}]}
// type类型 1 全替换 2 追加到后面 3 返回html
function initAnswer(class_name, data_list, type){
    var htmlStr = resolve_baidu_template(DYNAMIC_BAIDU_TEMPLATE,data_list,'');//解析
    if(type == 3) return htmlStr;
    //alert(htmlStr);
    //alert(body_data_id);
    if(type == 1){
        $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).html(htmlStr);
    }else if(type == 2){
        $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).append(htmlStr);
    }
}

(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var now_staff = item.now_staff;");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td><%=item.work_num%><\/td>");
    document.write("        <td><%=item.department_name%>\/<%=item.group_name%><\/td>");
    document.write("        <td><%=item.real_name%><\/td>");
    document.write("        <td><%=item.sex_text%><\/td>");
    document.write("        <td><%=item.position_name%><\/td>");
    document.write("        <td><%=item.mobile%><\/td>");
    document.write("        <td><%=item.answer_begin_time%><br/><%=item.answer_end_time%><\/td>");
    document.write("        <td><%=item.answer_minute%><\/td>");
    document.write("        <td>");
    document.write("            <%=item.pass_text%>");
    document.write("");
    document.write("            <%if( item.status > 1){%>");
    document.write("            (<%=item.exam_results%>分)");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();
