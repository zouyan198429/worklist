
$(function(){
    //回复
    $(document).on("click",".reply_page",function(){
        var obj = $(this);
        var id = obj.data('id');
        var weburl = REPLY_URL + id;
        var tishi = REPLY_TITLE;
        layeriframe(weburl,tishi,950,600,0);
        return false;
    })

});

(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <!-- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var can_modify = false;");
    document.write("    if( item.status == 0 ){");
    document.write("    can_modify = true;");
    document.write("    }");
    document.write("    %>");
    document.write("");
    document.write("    <tr>");
    document.write("        <td><%=item.type_name%>\/<%=item.business_name%><\/td>");
    document.write("        <td><%=item.content%><\/td>");
    // document.write("        <td><%=item.reply_content%><\/td>");
 //   document.write("        <td><%=item.call_number%><\/td>");
    // document.write("        <td><%=item.city_name%><%=item.area_name%><%=item.address%><\/td>");
    document.write("        <td><%=item.department_name%>/<%=item.group_name%><\/td>");
    document.write("        <td><%=item.operate_staff_name%><\/td>");
    document.write("        <td><%=item.created_at%><\/td>");
    // document.write("        <td>");
    // document.write("            <%if( can_modify){%>");
    // document.write("            <a href=\"javascript:void(0);\" data-id=\"<%=item.id%>\"  class=\"btn btn-mini reply_page\" >回复<\/a>");
    // document.write("            <%}%>");
    // document.write("        <\/td>");
    document.write("    <\/tr> <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();