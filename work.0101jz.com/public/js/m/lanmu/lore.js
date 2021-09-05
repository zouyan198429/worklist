
$(function(){
    //提交
    $(document).on("click",".type_click",function(){
        var obj = $(this);
        var type_id = obj.data('type_id');
        console.log(type_id);
        // 获得兄弟姐妹
        obj.siblings().removeClass("on");
        obj.addClass("on");
        $('select[name=type_id]').val(type_id);
        $(".search_frm").click();
        return false;
    });

});

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
    document.write("                <span><%=item.level_num_text%><\/span>");
    document.write("            <\/div>");
    document.write("            <div class=\"view\"><i class=\"fa fa-eye fa-fw\" aria-hidden=\"true\"><\/i> <%=item.volume%><\/div>");
    document.write("        <\/a>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();