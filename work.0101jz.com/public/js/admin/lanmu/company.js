
// $(function(){
window.onload = function() {

    //查询
    // $('.search_frm_self').click(function(){
    //     $("#"+PAGE_ID).val(1);//重归第一页
    //     //获得搜索表单的值
    //     append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
    //     // reset_list(false, true);
    //     reset_list_self(false,false);
    // });
    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false,false);
    // alert(111);
};

//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步  需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
function reset_list_self(is_read_page, ajax_async){
    console.log('is_read_page', typeof(is_read_page));
    console.log('ajax_async', typeof(ajax_async));
    // alert(666);
    reset_list(is_read_page, false);
    initPic();
}
// window.onload = function() {
//     initPic();
// };
function initPic(){
   // baguetteBox.run('.baguetteBoxOne');
    // baguetteBox.run('.baguetteBoxTwo');
    // alert(222);
    $('.qrcode').each(function(){
        let qrObj = $(this);
        // 交付二维码
        var qrcodeurl = qrObj.data('qrcodeurl');
        consoleLogs(['qrcodeurl', qrcodeurl]);
        if(qrcodeurl != ''){
            // showQRCodeTable('qrcode', qrcodeurl, 250, 250);// 显示付款二维码
            // qrObj.qrcode(toUtf8QRCode(qrcodeurl)); //任意字符串
            qrObj.qrcode({
                render: "canvas", //table方式
                width: 150, //宽度
                height:150, //高度
                text: toUtf8QRCode(qrcodeurl) //任意内容
            });
        }
    });
}
$(function(){
    // alert(111);
});
//业务逻辑部分
var otheraction = {

    copyWebUrl :function(thisObj) {
        var btnObj = $(thisObj);
        var htmlStr = btnObj.closest('tr').find('.web_url').text();
        console.log('==htmlStr===', htmlStr);
        copyToClip( thisObj, htmlStr);
        // Clipboard.copy(thisObj,htmlStr,'复制成功！！');
    },
    copyH5Url:function(thisObj) {
        var btnObj = $(thisObj);
        var htmlStr = btnObj.closest('tr').find('.h5_url').text();
        console.log('==htmlStr===', htmlStr);
        copyToClip( thisObj, htmlStr);
        // Clipboard.copy(thisObj,htmlStr,'复制成功！！');
    }

};
(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%> -->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td><%=item.company_name%><\/td>");
    document.write("        <td><%=item.module_no_text%><\/td>");
    document.write("        <td><%=item.department_name%><\/td>");
    document.write("        <td><%=item.open_status_text%><\/td>");
    document.write("        <td><%=item.company_linkman%>(<%=item.sex_text%>)<br/><%=item.company_mobile%><\/td>");
    document.write("        <td><%=item.company_status_text%><\/td>");
    document.write("        <td><%=item.company_vipend%><\/td>");
    document.write("        <td>");
    document.write("        <span class='web_url' ><%=item.webLoginUrl%></span>");
    document.write("        <input type=\"button\" class=\"btn btn-success  btn-xs export_excel\"  value=\"复制\"  onclick=\"otheraction.copyWebUrl(this)\">");
    document.write("        <span class=\"qrcode\" data-qrcodeurl=\"<%=item.webLoginUrl%>\"></span>");
    document.write("        <hr/><span class='h5_url' ><%=item.mLoginUrl%></span>");
    document.write("        <input type=\"button\" class=\"btn btn-success  btn-xs export_excel\"  value=\"复制\"  onclick=\"otheraction.copyH5Url(this)\">");
    document.write("        <span class=\"qrcode\" data-qrcodeurl=\"<%=item.mLoginUrl%>\"></span>");
    document.write("        <\/td>");
    document.write("        <td>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.edit(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\"> 编辑<\/i>");
    document.write("            <\/a>");
    document.write("            <%if( item.id != 1){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"action.del(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60\"> 删除<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();
