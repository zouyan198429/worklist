(function() {
	document.write("<!-- 前端模板部分 -->");
	document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
	document.write("<script type=\"text\/template\"  id=\"baidu_template_msg_list\">");
	document.write("    <%for(var i = 0; i<data_list.length;i++){");
	document.write("    var item = data_list[i];");
	document.write("    %>");
	document.write("    <li class=\"item\">");
	document.write("        <div class=\"con\">  <i class=\"fa fa-bell-o  fa-fw\" aria-hidden=\"true\"><\/i><%=item.mst_content%><\/div>");
	document.write("        <div class=\"btnbox2\"><a href=\"#\" class=\"btn smg_sure\" data-id=\"<%=item.id%>\">收到<\/a><\/div>");
	document.write("        <div class=\"c\"><\/div>");
	document.write("    <\/li>");
	document.write("    <%}%>");
	document.write("<\/script>");
	document.write("<!-- 列表模板部分 结束-->");
	document.write("<!-- 前端模板结束 -->");
}).call();