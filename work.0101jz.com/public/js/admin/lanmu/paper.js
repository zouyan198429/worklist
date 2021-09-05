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
    document.write("        <td style=\"width:90px;\">");
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
    document.write("            <%if( false){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"action.show(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-check bigger-60\"> 查看<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.edit(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("            <\/a>");
    document.write("            <%if( can_modify){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();