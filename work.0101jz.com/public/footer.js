(function() {
	document.write("<!-- 前端模板部分 -->");
	document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
	document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
	document.write("    <%for(var i = 0; i<data_list.length;i++){");
	document.write("    var item = data_list[i];");
	document.write("    %>");
	document.write("    <tr>");
	document.write("        <td><%=item.count_date%><\/td>");
	document.write("        <td><%=item.amount%><\/td>");
	document.write("    <\/tr>");
	document.write("    <%}%>");
	document.write("<\/script>");
	document.write("<!-- 列表模板部分 结束-->");
	document.write("<!-- 前端模板结束 -->");
}).call();