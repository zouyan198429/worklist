(function() {
    document.write("");
    document.write("    <!-- 前端模板部分 -->");
    document.write("    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("    <script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("        <%for(var i = 0; i<data_list.length;i++){");
    document.write("        var item = data_list[i];");
    //document.write("        var can_modify = false;");
    //document.write("        if( item.issuper==0 ){");
    document.write("        can_modify = true;");
   // document.write("        }");
    document.write("        %>");
    document.write("");
    document.write("        <tr>");
    document.write("            <td><a href=\"javascript:void(0);\" onclick=\"action.show(<%=item.id%>)\"><%=item.title%></a><\/td>");
    document.write("            <td><%=item.created_at%><\/td>");
    document.write("            <td><%=item.real_name%><\/td>");
    document.write("            <td><%=item.volume%><\/td>");
    document.write("        <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();