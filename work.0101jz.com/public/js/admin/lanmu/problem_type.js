(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%> -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td><%=item.type_name%><\/td>");
    document.write("        <td><%=item.business_name%><\/td>");
    document.write("        <td><%=item.sort_num%><\/td>");
    document.write("        <td>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.edit(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("            <\/a>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    document.write("            <\/a>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();