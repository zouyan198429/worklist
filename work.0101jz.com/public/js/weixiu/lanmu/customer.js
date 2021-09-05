
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list(false, true);
    //提交
    $(document).on("click",".selType_click",function(){
        var obj = $(this);
        var seltype = obj.data('seltype');
        console.log(seltype);
        // 获得兄弟姐妹
        obj.siblings().removeClass("on");
        obj.addClass("on");
        $('select[name=sel_type]').val(seltype);
        $(".search_frm").click();
        return false;
    });
    // 标记操作
    $(document).on("click",".tab_oprate",function(){
        var obj = $(this);
        var id = obj.data('id');
        var is_tab = obj.data('is_tab');
        console.log("id:",id);
        console.log("is_tab:",is_tab);
        var tishi = "标记";
        if(is_tab == 1 ){
            tishi = "取消标记";
        }
        var index_query = layer.confirm('确定' + tishi + '当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //标记操作
            // operate_ajax('batch_del',ids);
            var data = {'is_tab':is_tab};
            if(is_tab == 1 ){
                list_operate_ajax('removeTag', id, data);// 取消标记
            }else{
                list_operate_ajax('addTag', id, data);// 标记
            }
            layer.close(index_query);
        }, function(){
        });
        return false;
    });

});

//列表操作
// data 其它参数 格式 {'参数名':'参数值',...}
function list_operate_ajax(operate_type,id,data){
    if(operate_type=='' || id==''){
        err_alert('请选择需要操作的数据');
        return false;
    }
    var operate_txt = "";
    // var data ={};
    var ajax_url = "";
    switch(operate_type)
    {
        case 'addTag'://标记
            operate_txt = "标记";
            // data = {'id':id};
            data['id'] = id;
            ajax_url = TAG_URL;
            break;
        case 'removeTag'://取消标记
            operate_txt = "取消标记";
            // data = {'id':id};
            data['id'] = id;
            ajax_url = TAG_URL;
            break;
        // case 'del'://删除
        //     operate_txt = "删除";
        //     data = {'id':id}
        //     data['id'] = id;
        //     ajax_url = DEL_URL;// /pms/Supplier/ajax_del?operate_type=1
        //     break;
        // case 'batch_del'://批量删除
        //     operate_txt = "批量删除";
        //     data = {'id':id}
        //     data['id'] = id;
        //     ajax_url = BATCH_DEL_URL;// "/pms/Supplier/ajax_del?operate_type=2";
        //     break;
        default:
            break;
    }
    var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
    $.ajax({
        'type' : 'POST',
        'url' : ajax_url,//'/pms/Supplier/ajax_del',
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            if(!ret.apistatus){//失败
                //alert('失败');
                // countdown_alert(ret.errorMsg,0,5);
                layer_alert(ret.errorMsg,3,0);
            }else{//成功
                var msg = ret.errorMsg;
                if(msg === ""){
                    msg = operate_txt+"成功";
                }
                // countdown_alert(msg,1,5);
                layer_alert(msg,1,0);
                reset_list(true, true);
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <!-- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var is_tab = item.is_tab;");
    document.write("    var can_modify = false;");
    document.write("   <!--  if( item.issuper==0 ){-->");
    document.write("   <!-- can_modify = true;-->");
    document.write("   <!--  }-->");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td><%=item.call_number%><\/td>");
    document.write("        <td><%=item.contact_number%><\/td>");
    document.write("        <td><%=item.address%><\/td>");
    document.write("        <td><%=item.call_num%><\/td>");
    document.write("        <td><%=item.last_call_date%><\/td>");
    document.write("        <td>");
    document.write("            <%if( is_tab == 1){%>");
    document.write("            <span  style=\"color: red;\"><%=item.is_tab_text%><\/span>");
    document.write("            <%}else{%>");
    document.write("            <%=item.is_tab_text%>");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("        <td>");
    document.write("            <%if( is_tab == 1){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn tab_oprate\" data-id=\"<%=item.id%>\"  data-is_tab=\"<%=item.is_tab%>\">取消标记<\/a>");
    document.write("            <%}else{%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn tab_oprate\" data-id=\"<%=item.id%>\"  data-is_tab=\"<%=item.is_tab%>\">标记<\/a>");
    document.write("            <%}%>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();