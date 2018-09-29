
$(function(){
    //提交
    $(document).on("click",".status_click",function(){
        var obj = $(this);
        var status = obj.data('status');
        console.log(status);
        // 获得兄弟姐妹
        obj.siblings().removeClass("on");
        obj.addClass("on");
        $('select[name=status]').val(status);
        $(".search_frm").click();
        return false;
    });

});

(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    can_modify = false;");
    document.write("    %>");
    document.write("");
    document.write("    <tr>");
    document.write("        <td><%=item.work_num%>");
    document.write("        <br\/><a href=\"tel:<%=item.call_number%>\" class=\"btn\" ><%=item.call_number%> <i class=\"fa fa-phone-square fa-fw\" aria-hidden=\"true\"><\/i> <\/a>");
    document.write("            <%if( item.contact_number != ''){%>");
    document.write("        <br\/><a href=\"tel:<%=item.contact_number%>\" class=\"btn\" ><%=item.contact_number%> <i class=\"fa fa-phone-square fa-fw\" aria-hidden=\"true\"><\/i> <\/a>");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("        <td><%=item.caller_type_name%><\/td>");
    document.write("        <td><%=item.type_name%>\/<%=item.business_name%><\/td>");
    document.write("        <td><%=item.content%><\/td>");
    document.write("        <td><%=item.city_name%><%=item.area_name%><%=item.address%><\/td>");
    document.write("        <td><%=item.created_at%><\/td>");
    document.write("        <td><%=item.time_name%><br\/>[<%=item.expiry_time%>]<\/td>");
    document.write("        <td><%=item.department_name%>/<%=item.group_name%><br\/><%=item.real_name%><\/td>");
    document.write("        <td><%=item.send_department_name%>/<%=item.send_group_name%><br\/><%=item.send_real_name%><\/td>");
    document.write("        <td><%=item.status_text%><\/td>");//剩余1小时12分
    document.write("        <td>");
    document.write("            <%if( true){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-success\"  onclick=\"action.show(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-check bigger-60\"> 查看<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("            <%if( can_modify){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.edit(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("            <\/a>");
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