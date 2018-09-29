
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    $('.search_frm').trigger("click");// 触发搜索事件
    ajax_status_count(0, 0, 0);//ajax工单状态统计
    reset_list(false);
    // 自动更新数据
    var autoObj = new Object();
    autoObj.orderProcessList = function(){
        ajax_status_count(1, 0, 0);//ajax工单状态统计
    };
    setInterval(autoObj.orderProcessList,60000);
});

//ajax工单状态统计
// from_id 来源 0 页面第一次加载,不播放音乐 1 每分钟获得数量，有变化，播放音乐

function ajax_status_count(from_id ,staff_id, operate_staff_id){
    // if (!SUBMIT_FORM) return false;//false，则返回

    // 验证通过
    // SUBMIT_FORM = false;//标记为已经提交过
    var data = {
        'staff_id': staff_id,
        'operate_staff_id': operate_staff_id,
    };
    console.log(SATUS_COUNT_URL);
    console.log(data);
    if( from_id == 0)  var layer_count_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SATUS_COUNT_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var statusCount = ret.result;
                console.log(statusCount);
                var doStatus = NEED_PLAY_STATUS;
                var doStatusArr = doStatus.split(',');

                // 遍历
                var needPlay = false;// true：播放；false:不播放
                var selected_status = $('select[name=status]').val();
                for(var temStatus in statusCount){//遍历json对象的每个key/value对,p为key
                    var countObj = $(".status_count_" + temStatus );
                    if(countObj.length <= 0) continue;
                    var temCount = statusCount[temStatus];
                    var oldCount = countObj.data('old_count');
                    console.log(oldCount);
                    console.log(temCount);
                    if(oldCount != temCount){
                        countObj.html(temCount);
                        countObj.data('old_count', temCount);
                        console.log('new_order');
                        // 数量变大了
                        if( from_id == 1 && (!needPlay) && temCount > oldCount  && doStatusArr.indexOf(temStatus) >= 0){
                            needPlay = true;
                        }

                        // 刷新列表-当前页
                        if( from_id == 1 && selected_status == temStatus){
                            console.log('刷新列表-当前页');
                            reset_list(true);
                        }
                    }
                }
                if(needPlay && from_id == 1){// 播放
                    console.log('播放提示音');
                    run_sound('new_order');
                }

                // status_count_
            }
            // SUBMIT_FORM = true;//标记为未提交过
            if( from_id == 0)   layer.close(layer_count_index);//手动关闭
        }
    });
    return false;
}


// var  SUBMIT_FORM = true;
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
    })

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

        var weburl = WIN_WORK_PAGE_URL + id;
        console.log(weburl);
        var tishi = WIN_TITLE;
        layeriframe(weburl,tishi,950,600,0);
        // var index_query = layer.confirm('确认结单？', {
        //     btn: ['确定','取消'] //按钮
        // }, function(){
        // work_operate_ajax('sure_word',id);
        // go(WIN_WORK_PAGE_URL + id);
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
                    // parent.location.reload()// 刷新当前页
                    ajax_status_count(1, 0, 0);//ajax工单状态统计
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
    document.write("");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var status = item.status;");
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
    document.write("            <%if( status == 1){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info work_sure\"  data-id=\"<%=item.id%>\" >");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\"> 确认<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("            <%if( status == 2){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info work_win\"  data-id=\"<%=item.id%>\" >");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60\" > 结单<\/i>");
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
