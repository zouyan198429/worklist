
(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("    <li>");
    document.write("        <a href=\"javascript:void(0);\"  onclick=\"action.urlshow(<%=item.id%>)\">");
    document.write("            <div class=\"title\" >");
    document.write("                <p><i class=\"fa fa-angle-right  fa-fw\" aria-hidden=\"true\"><\/i> <%=item.title%><\/p>");
    document.write("                <span><%=item.created_at%><\/span>");
    document.write("            <\/div>");
    document.write("            <div class=\"view\"><i class=\"fa fa-eye fa-fw\" aria-hidden=\"true\"><\/i> <%=item.volume%><\/div>");
    document.write("        <\/a>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();