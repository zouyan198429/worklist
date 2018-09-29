
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    //提交
    $(document).on("click",".status_click",function(){
        var obj = $(this);
        var status = obj.data('status');
        console.log(status);
        // 获得兄弟姐妹
        obj.siblings().removeClass("on");
        obj.addClass("on");
        $('input[name=status]').val(status);
        $(".search_frm").click();
        return false;
    });

    //消息确认
    $(document).on("click",".smg_sure",function(){
        var obj = $(this);
        var id = obj.data('id');
        var index_query = layer.confirm('确定收到消息并已查看该消息？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            work_operate_ajax('sure_msg',id);
            layer.close(index_query);
        }, function(){
        });
        return false;
    });

    // 确认工单
    $(document).on("click",".work_sure",function(){
        var obj = $(this);
        var id = obj.data('id');
        var index_query = layer.confirm('确认工单？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            work_operate_ajax('sure_word',id);
            layer.close(index_query);
        }, function(){
        });
        return false;
    });

    // 确认结单
    $(document).on("click",".work_win",function(){
        var obj = $(this);
        var id = obj.data('id');
        // var index_query = layer.confirm('确认结单？', {
        //     btn: ['确定','取消'] //按钮
        // }, function(){
        // work_operate_ajax('sure_word',id);
        go(WIN_WORK_PAGE_URL + id);
        // 跳转让到内容输入页
        // alert(id);
        //     layer.close(index_query);
        // }, function(){
        // });
        return false;
    });
});

//操作
function work_operate_ajax(operate_type,id){
    if (!SUBMIT_FORM) return false;//false，则返回
    SUBMIT_FORM = false;//标记为已经提交过
    if(operate_type=='' || id==''){
        err_alert('请选择需要操作的数据');
        SUBMIT_FORM = true;//标记为已经提交过
        return false;
    }
    var operate_txt = "";
    var data ={};
    var ajax_url = "";
    switch(operate_type)
    {
        case 'sure_msg'://消息确认
            operate_txt = "消息确认";
            data = {'id':id}
            ajax_url = SURE_MSG_URL;
            break;
        case 'sure_word'://确认工单
            operate_txt = "确认工单";
            data = {'id':id}
            ajax_url = SURE_WORK_URL;// "/pms/Supplier/ajax_del?operate_type=2";
            break;
        case 'win_word'://确认结单
            operate_txt = "确认结单";
            data = {'id':id}
            ajax_url = WIN_WORK_URL;// "/pms/Supplier/ajax_del?operate_type=2";
            break;
        default:
            break;
    }
    var layer_index = layer.load();//layer.msg('加载中', {icon: 16});
    console.log(ajax_url);
    console.log(data);
    $.ajax({
        'type' : 'POST',
        'url' : ajax_url,//'/pms/Supplier/ajax_del',
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            if(!ret.apistatus){//失败
                SUBMIT_FORM = true;//标记为已经提交过
                //alert('失败');
                // countdown_alert(ret.errorMsg,0,5);
                layer_alert(ret.errorMsg,3,0);
            }else{//成功
                var msg = ret.errorMsg;
                if(msg === ""){
                    msg = operate_txt+"成功";
                }
                layer.msg(msg, {
                    icon: 1,
                    shade: 0.3,
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    SUBMIT_FORM = true;//标记为已经提交过
                    //刷新当前页面
                    parent.location.reload()// 刷新当前页
                    // alert(1234);
                    //do something
                });

                // countdown_alert(msg,1,5);
                //layer_alert(msg,1,0);
                //reset_list(true);
            }
            layer.close(layer_index)//手动关闭
        }
    });
}


(function() {
    document.write("");
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var status = item.status;");
    document.write("    %>");
    document.write("");
    document.write("    <div class=\"gd-list\" >");
    document.write("        <div class=\"gd-hd\">");
    document.write("            <p>");
    document.write("                <span class=\"khname\"><i class=\"fa fa-user-circle-o fa-fw\" aria-hidden=\"true\"><\/i> <%=item.customer_name%>(<%=item.sex_text%>) <\/span>");

    document.write("            <%if( item.contact_number == ''){%>");
    document.write("                <a href=\"tel:<%=item.call_number%>\" class=\"btnnb fr\" ><i class=\"fa fa-phone fa-fw\" aria-hidden=\"true\"><\/i> <%=item.call_number%><\/a>");
    document.write("            <%}else{%>");
    document.write("                <a href=\"tel:<%=item.contact_number%>\" class=\"btnnb fr\" ><i class=\"fa fa-phone fa-fw\" aria-hidden=\"true\"><\/i> <%=item.contact_number%><\/a>");
    document.write("            <%}%>");
    document.write("        <\/div>");
    document.write("        <div class=\"gd-bd\">");
    document.write("            <p><i class=\"fa fa-flag fa-fw\" aria-hidden=\"true\"><\/i>  工单类型：<%=item.type_name%>--<%=item.business_name%><\/p>");
    document.write("            <p class=\"khtip\"><%=item.content%>");
    document.write("            <\/p>");
    document.write("            <p>");
    document.write("                <span class=\"gdtime\"><i class=\"fa fa-clock-o fa-fw\" aria-hidden=\"true\"><\/i> 报修时间：<%=item.created_at%><\/span>");
    document.write("                <span class=\"gdtime\"> 到期时间：<%=item.expiry_time%><\/span>");
    document.write("            <\/p>");
    document.write("        <\/div>");
    document.write("        <div class=\"gd-fd\">");
    document.write("            <i class=\"fa fa-map-marker fa-fw\" aria-hidden=\"true\"><\/i> <%=item.city_name%><%=item.area_name%><%=item.address%> <\/p>");
    document.write("        <\/div>");
    document.write("        <div class=\"btnbox\">");
    document.write("            <%if( status == 1){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn fr work_sure\" data-id=\"<%=item.id%>\" >确认<\/a>");
    document.write("            <%}%>");
    document.write("            <%if( status == 2){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn fr work_win\" data-id=\"<%=item.id%>\" >结单<\/a>");
    document.write("            <%}%>");
    document.write("            <div class=\"c\"><\/div>");
    document.write("        <\/div>");
    document.write("    <\/div>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();