
//业务逻辑部分
var otheraction = {
    doing: function(id){// 进入答题
        go(DOING_EXAM_URL + id);
        return false;
    },
};

function countTime() {
    $('#data_list').find('li').each(function () {
        var trObj = $(this);
        // var date = new Date();
        // var now = date.getTime();
        // var endDate = new Date("2018-10-30 00:00:00");//设置截止时间
        // var end = endDate.getTime();
        // var leftTime = end - now; //时间差

        var status = trObj.data('status');// 状态

        var date = new Date();
        var now = date.getTime();

        var exam_begin_time = trObj.data('exam_begin_time');// 开始时间
        var beginDate = new Date(exam_begin_time);//设置开始时间
        var begin = beginDate.getTime();

        // var exam_end_time = trObj.data('exam_end_time');// 结束时间
        // var endDate = new Date(exam_end_time);//设置截止时间
        // var end = endDate.getTime();
        var leftTime = begin - now; //时间差
        var d, h, m, s, ms;
        if (status == 1 && leftTime >= 0) {
            d = Math.floor(leftTime / 1000 / 60 / 60 / 24);
            h = Math.floor(leftTime / 1000 / 60 / 60 % 24);
            m = Math.floor(leftTime / 1000 / 60 % 60);
            s = Math.floor(leftTime / 1000 % 60);
            ms = Math.floor(leftTime % 1000);
            if (ms < 100) {
                ms = "0" + ms;
            }
            if (s < 10) {
                s = "0" + s;
            }
            if (m < 10) {
                m = "0" + m;
            }
            if (h < 10) {
                h = "0" + h;
            }
            trObj.find('._d').html(d + "天");
            trObj.find('._h').html(h + "时");
            trObj.find('._m').html(m + "分");
            trObj.find('._s').html(s + "秒");
            trObj.find('._ms').html(ms + "毫秒");
            //将倒计时赋值到div中
            // document.getElementById("_d").innerHTML = d + "天";
            // document.getElementById("_h").innerHTML = h + "时";
            // document.getElementById("_m").innerHTML = m + "分";
            // document.getElementById("_s").innerHTML = s + "秒";
            // document.getElementById("_ms").innerHTML = ms + "毫秒";
            trObj.find('.exam_time').show();
            trObj.find('.exam_doing').hide();
        } else if(status == 1 || status == 2){
            trObj.find('.exam_time').hide();
            trObj.find('.exam_doing').show();
            // console.log('已截止')
        }else{
            trObj.find('.exam_time').hide();
            trObj.find('.exam_doing').hide();
        }
    });
    //递归每秒调用countTime方法，显示动态时间效果
    setTimeout(countTime, 50);
}
countTime();


(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var status = item.status;");
    document.write("    var can_modify = false;");
    document.write("    if(status == 1 || status == 2){");
    document.write("    can_modify = true;");
    document.write("    }");
    document.write("    %>");
    document.write("    <li  data-exam_begin_time=\"<%=item.exam_begin_time%>\" data-exam_end_time=\"<%=item.exam_end_time%>\"  data-status=\"<%=item.status%>\" >");
    document.write("");
    document.write("        <h3><%=item.exam_num%><\/h3>");
    document.write("        <dl>");
    document.write("            <dt>开考日期<\/dt>");
    document.write("            <dd><%=item.exam_begin_time%><\/dd>");
    document.write("        <\/dl>");
    document.write("        <dl>");
    document.write("            <dt>考试时长<\/dt>");
    document.write("            <dd><%=item.exam_minute%>分钟<\/dd>");
    document.write("        <\/dl>");
    document.write("        <dl>");
    document.write("            <dt>考试主题<\/dt>");
    document.write("            <dd><%=item.exam_subject%><\/dd>");
    document.write("        <\/dl>");
    document.write("        <dl>");
    document.write("            <dt>状态<\/dt>");
    document.write("            <dd><%=item.status_text%><\/dd>");
    document.write("        <\/dl>");
    document.write("        <div class=\"c\"><\/div>");
    document.write("        <div class=\"btnbox\">");
    document.write("            <%if( can_modify){%>");
    document.write("            <button class=\"exam_time\">");
    document.write("                <i class=\"fa fa-clock-o fa-fw\" aria-hidden=\"true\"><\/i>");
    document.write("                距离开考：");
    document.write("                <span class=\"_d\">00<\/span>");
    document.write("                <span class=\"_h\">00<\/span>");
    document.write("                <span class=\"_m\">00<\/span>");
    document.write("                <span class=\"_s\">00<\/span>");
    document.write("                <span class=\"_ms\">00<\/span>");
    document.write("            <\/button>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn exam_doing\"  onclick=\"otheraction.doing(<%=item.id%>)\"  >进入<\/a>");
    document.write("            <%}%>");
    document.write("            <%if( false && can_modify){%>");
    document.write("            2天3小时33分钟");
    document.write("            <a href=\"examin_cj.html\" class=\"btn\" >查看成绩<\/a>");
    document.write("            <%}%>");
    document.write("");
    document.write("        <\/div>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();