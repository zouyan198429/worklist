
// $(function(){
    window.onload = function() {
    //回复
    $(document).on("click",".reply_page",function(){
        var obj = $(this);
        var id = obj.data('id');
        var weburl = REPLY_URL + id;
        var tishi = REPLY_TITLE;
        layeriframe(weburl,tishi,950,600,0);
        return false;
    })
    $('.search_frm').trigger("click");// 触发搜索事件
    }
// });

//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步  需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
function reset_list_self(is_read_page, ajax_async){
    console.log('is_read_page', typeof(is_read_page));
    console.log('ajax_async', typeof(ajax_async));
    var layer_index = layer.load();
    reset_list(is_read_page, false);
    initPic();
    layer.close(layer_index)//手动关闭
}
// window.onload = function() {
//     initPic();
// };
function initPic(){
    baguetteBox.run('.baguetteBoxOne');
    // baguetteBox.run('.baguetteBoxTwo');
}

(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <!-- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var resource_list = item.resource_list;");
    document.write("    var can_modify = false;");
    document.write("    if( item.status==0 ){");
    document.write("    can_modify = true;");
    document.write("    }");
    document.write("    %>");
    document.write("");
    document.write("    <tr>");
    document.write("        <td><%=item.type_name%>\/<%=item.business_name%><\/td>");
    document.write("        <td>");
    document.write("    <%for(var j = 0; j<resource_list.length;j++){");
    document.write("    var jitem = resource_list[j];");
    document.write("    %>");
    document.write("       <a href=\"<%=jitem.resource_url%>\">");
    document.write("       <img  src=\"<%=jitem.resource_url%>\"  style=\"width:100px;\">");
    document.write("       </a>");
    document.write("    <%}%>");
    document.write("        <\/td>");
    document.write("        <td><%=item.content%><\/td>");
    document.write("        <td><%=item.reply_content%><\/td>");
 //   document.write("        <td><%=item.call_number%><\/td>");
 //   document.write("        <td><%=item.city_name%><%=item.area_name%><%=item.address%><\/td>");
    document.write("        <td><%=item.department_name%>/<%=item.group_name%><\/td>");
    document.write("        <td><%=item.operate_staff_name%><\/td>");
    document.write("        <td><%=item.created_at%><\/td>");
    document.write("        <td><%=item.status_text%><\/td>");
    document.write("        <td>");
    document.write("            <%if( can_modify){%>");
    document.write("            <a href=\"javascript:void(0);\" data-id=\"<%=item.id%>\" class=\"btn btn-mini reply_page\" >回复<\/a>");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("    <\/tr> <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();