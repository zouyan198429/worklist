
// 修改时，补始化数据
// upload_id 上传控制id
// baidu_tem_name 百度模板的名称
// picobj 数据列表json对象 结构 {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}
function init_upload_pic(upload_id,baidu_tem_name,picobj){
    var htmlStr = '';//
    htmlStr = resolve_baidu_template(baidu_tem_name,picobj,'');
    $('#' + upload_id).closest('.resourceBlock').find(".upload_img").append(htmlStr);
}
// 根据id删除资源
// type 类型 1 上传控件2自定义的资源对象
// obj type的对象
// $key 资源区块的标认- 多个时备用[可能会用得上]
function delResource(resource_id, type, obj, $key){
    var data = {'id':resource_id,'key':$key};
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : PIC_DEL_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                switch (type){
                    case 1:
                        obj();//doRemoveFile();
                        break;
                    case 2:
                        obj.remove();
                        var uploader = $('#'+ $key).data('zui.uploader');
                        var files = uploader.getFiles();
                        console.log('this对象变动的总数limitFilesCount', uploader.options.limitFilesCount);
                        uploader.options.limitFilesCount++;
                        break;
                    default:
                }
            }
            layer.close(layer_index)//手动关闭
        }
    })
}

(function() {
    document.write("");
    document.write("<!-- 加载中模板部分 开始-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_pic_show\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <div class=\"col-md-4 col-sm-6 col-lg-3 resource\">");
    document.write("        <div class=\"card \">");
    document.write("            <img data-toggle=\"lightbox\" src=\"<%=item.resource_url%>\" alt=\"\">");
    document.write("            <div class=\"pre with-padding clearfix\">");
    document.write("                <h4 class=\"text-ellipsis\"><%=item.resource_name%><\/h4>");
    document.write("               ");
    document.write("                <i class=\"icon icon-times pull-right delResource\"  data-id=\"<%=item.id%>\"><\/i>");
    document.write("                <label class=\"checkbox-inline\"  style=\"display:none;\">");
    document.write("                    <input type=\"checkbox\" value=\"<%=item.id%>\" name=\"resource_id[]\" checked=\"checked\">");
    document.write("                <\/label>");
    document.write("            <\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 加载中模板部分 结束-->");
}).call();