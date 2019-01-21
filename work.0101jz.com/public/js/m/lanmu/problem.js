
// $(function(){

window.onload = function() {
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
    $('.search_frm').trigger("click");// 触发搜索事件
};
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
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var resource_list = item.resource_list;");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("    <li>");
    document.write("   <dl class=\"inp\"> ");
    document.write("    <dt>图片：</dt>");
    document.write("    <dd>");
    document.write("    <%for(var j = 0; j<resource_list.length;j++){");
    document.write("    var jitem = resource_list[j];");
    document.write("    %>");
    document.write("       <a href=\"<%=jitem.resource_url%>\">");
    document.write("       <img  src=\"<%=jitem.resource_url%>\"  style=\"width:100px;\">");
    document.write("       </a>");
    document.write("    <%}%>");
    document.write("    </dd>");
    document.write("    </dl>");
    document.write("   <dl class=\"inp\"> ");
    document.write("    <dt>问题分类：<%=item.type_name%>/<%=item.business_name%> &nbsp;&nbsp;&nbsp;&nbsp;状态：<%=item.status_text%></dt>");
    // document.write("    <dd><%=item.type_name%>/<%=item.business_name%>");
    // document.write("    </dd>");
    document.write("    </dl>");
    document.write("   <dl class=\"inp\"> ");
    document.write("    <dt>反馈时间：<%=item.created_at%></dt>");
    // document.write("    <dd><%=item.created_at%>");
    // document.write("    </dd>");
    document.write("    </dl>");
    // document.write("   <dl class=\"inp\"> ");
    // document.write("    <dt>状态：<%=item.status_text%></dt>");
    // // document.write("    <dd><%=item.status_text%>");
    // // document.write("    </dd>");
    // document.write("    </dl>");
    document.write("   <dl class=\"inp\"> ");
    document.write("    <dt>反馈内容：</dt>");
    document.write("    <dd><textarea  class=\"inptext wlong\" disabled='disabled'  style=\" height:100px;\"><%=item.content%></textarea>");
    document.write("    </dd>");
    document.write("    </dl>");
    document.write("   <dl class=\"inp\"> ");
    document.write("    <dt>回复内容：</dt>");
    document.write("    <dd><textarea  class=\"inptext wlong\"  disabled='disabled'  style=\" height:100px;\"><%=item.reply_content%></textarea>");
    document.write("    </dd>");
    document.write("    </dl><br\/>");
    // document.write("   <dl class=\"inp\"> ");
    // document.write("    <dt>反馈时间：<%=item.created_at%></dt>");
    // // document.write("    <dd><%=item.created_at%>");
    // // document.write("    </dd>");
    // document.write("    </dl>");

    // document.write("        <a href=\"javascript:void(0);\"  onclick=\"action.urlshow(<%=item.id%>)\">");
    // document.write("            <div class=\"title\" >");
    // document.write("                <p><i class=\"fa fa-angle-right  fa-fw\" aria-hidden=\"true\"><\/i> <%=item.content%><\/p>");
    // document.write("                <span><%=item.created_at%><\/span>");
    // document.write("            <\/div>");
    // document.write("            <div class=\"view\"><i class=\"fa fa-eye fa-fw\" aria-hidden=\"true\"><\/i> <%=item.volume%><\/div>");
    // document.write("        <\/a>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();