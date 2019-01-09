
$(function(){
    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false, false);
});
//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步  需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
function reset_list_self(is_read_page, ajax_async){
    console.log('is_read_page', typeof(is_read_page));
    console.log('ajax_async', typeof(ajax_async));
    reset_list(is_read_page, false);
    initList();
}
// 初始化
function initList(){
    // 获得选中的试题id 数组
    var SELECTED_IDS = parent.getSelectedPaperIds();
    console.log('SELECTED_IDS',SELECTED_IDS);
    $('#data_list').find('tr').each(function () {
        var trObj = $(this);
        // console.log(trObj.html());
        var checkedObj = trObj.find('.check_item');
        console.log('checkedObj', checkedObj.length);
        var paper_id = checkedObj.val();
        console.log('paper_id', paper_id);
        if(SELECTED_IDS.indexOf(paper_id) !== -1){// 已选
            trObj.find('.add').hide();
            trObj.find('.del').show();
            checkedObj.prop('disabled',true);
            checkedObj.prop('checked',false);
        }else{// 未选
            trObj.find('.add').show();
            trObj.find('.del').hide();
            checkedObj.prop('disabled',false);
        }

    });
}

//业务逻辑部分
var otheraction = {
    add : function(id){// 增加单个试题
        var index_query = layer.confirm('确定增加当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            parent.addPaper(id);
            initList();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    del : function(id){// 取消
        var index_query = layer.confirm('确定取消当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            parent.removePaper(id);
            initList();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
};


(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("");
    document.write("    <tr>");
    document.write("        <td style=\"width:90px;display: none\">");
    document.write("            <label class=\"pos-rel\">");
    document.write("                <input  onclick=\"action.seledSingle(this)\" type=\"checkbox\" class=\"ace check_item\" <%if( false &&  !can_modify){%> disabled <%}%>  value=\"<%=item.id%>\"\/>");
    document.write("                <span class=\"lbl\"><\/span>");
    document.write("            <\/label>");
    document.write("        <\/td>");
    document.write("        <td><%=item.paper_name%><\/td>");
    document.write("        <td><%=item.order_type_text%><\/td>");
    document.write("        <td><%=item.subjectTypeText%><\/td>");
    document.write("        <td><%=item.subject_amount%><\/td>");
    document.write("        <td><%=item.total_score%><\/td>");
    document.write("        <td><%=item.created_at%><\/td>");
    document.write("        <td><%=item.real_name%><\/td>");
    document.write("        <td>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info add \" onclick=\"otheraction.add(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-plus bigger-60\"> 选择<\/i>");
    document.write("            <\/a>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info del pink \" onclick=\"otheraction.del(<%=item.id%>)\">");
    document.write("               <i class=\"ace-icon fa fa-trash-o bigger-60\"> 取消<\/i>");
    document.write("            <\/a>");
    document.write("");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();