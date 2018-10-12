
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    $('.search_frm').trigger("click");// 触发搜索事件
    ajax_status_count(0, 0, 0);//ajax工单状态统计
    ajax_msg_list(0 ,0, 0);// ajax 消息列表
    // reset_list(false);
    // 自动更新数据
    var autoObj = new Object();
    autoObj.orderProcessList = function(){
        ajax_status_count(1, 0, 0);//ajax工单状态统计
    };
    autoObj.msgProcessList = function(){
        ajax_msg_list(1 ,0, 0);// ajax 消息列表
    };
    setInterval(autoObj.orderProcessList,60000);// 工单状态统计
    setInterval(autoObj.msgProcessList,60000);// 消息列表
});

//ajax工单状态统计
// from_id 来源 0 页面第一次加载,不播放音乐 1 每分钟获得数量，有变化，播放音乐

function ajax_status_count(from_id ,staff_id, operate_staff_id){
    // if (!SUBMIT_FORM) return false;//false，则返回

    // 验证通过
    //  SUBMIT_FORM = false;//标记为已经提交过
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

// ajax获得最新消息
//ajax工单状态统计
// from_id 来源 0 页面第一次加载,不播放音乐 1 每分钟获得数量，有变化，播放音乐
function ajax_msg_list(from_id ,staff_id, operate_staff_id){
    // if (!SUBMIT_FORM) return false;//false，则返回

    var msgObj = $("#msgList");
    var msg_id_arr = [];
    msgObj.find('li').each(function () {
        var msg_id = $(this).data('id');
        msg_id_arr.push(msg_id);
    });

    // 验证通过
    //  SUBMIT_FORM = false;//标记为已经提交过
    var data = {
        'msg_ids' :msg_id_arr.join(","),
        'staff_id': staff_id,
        'operate_staff_id': operate_staff_id,
    };
    console.log("扫描消息开始");
    console.log(MSG_LIST_URL);
    console.log(data);
    if( from_id == 0)  var layer_count_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : MSG_LIST_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var old_msg = msgObj.data('old_msg');
                console.log(old_msg);
                var add_count = ret.result.add_count;
                console.log(ret.result);
                if(add_count > 0){
                    var htmlStr = resolve_baidu_template('baidu_template_msg_list',ret.result,'');//解析
                    msgObj.prepend(htmlStr);
                    if(from_id == 1 ){// 播放
                        // console.log('msg-播放提示音');
                        // run_sound('new_order');
                    }
                }
                var del_ids = ret.result.del_ids;// 移除对象id
                for(var k = 0; k < del_ids.length; k++) {
                    var del_id = del_ids[k];
                    console.log('移除消息' + del_id);
                    $(".msg_" + del_id ).remove();
                }
                var total = ret.result.total;
                console.log('消息数量');
                console.log(total);
                // if(old_msg != total){
                //     msgObj.data('old_msg',total);
                //     if(from_id == 1 && total > old_msg){// 播放
                //         console.log('播放提示音');
                //         run_sound('msg-new_order');
                //     }
                // }
            }
            // SUBMIT_FORM = true;//标记为未提交过
            if( from_id == 0)   layer.close(layer_count_index);//手动关闭
        }
    });
    return false;
}


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
                    // parent.location.reload()// 刷新当前页
                    ajax_msg_list(0 ,0, 0);// ajax 消息列表
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

(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_msg_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <li class=\"item  msg_<%=item.id%> \"  data-id=\"<%=item.id%>\">");
    document.write("        <div class=\"con\">  <i class=\"fa fa-bell-o  fa-fw\" aria-hidden=\"true\"><\/i><%=item.mst_content%><\/div>");
    document.write("        <div class=\"btnbox2\"><a href=\"#\" class=\"btn smg_sure\" data-id=\"<%=item.id%>\">收到<\/a><\/div>");
    document.write("        <div class=\"c\"><\/div>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();