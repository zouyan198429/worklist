(function() {
	document.write("");
	document.write("<!-- 前端模板部分 -->");
	document.write("<!-- 列表模板部分 开始-->");
	document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
	document.write("    <%for(var i = 0; i<data_list.length;i++){");
	document.write("    var item = data_list[i];");
	document.write("    %>");
	document.write("    <tr>");
	document.write("        <td><%=item.id%><\/td>");
	document.write("        <td><a href=\"\/new\/<%=item.id%>\" ><%=item.new_title%><\/a><\/td>");
	document.write("        <td><%=item.created_at%><\/td>");
	document.write("    <\/tr>");
	document.write("    <%}%>");
	document.write("<\/script>");
	document.write("<!-- 列表模板部分 结束-->");
}).call();