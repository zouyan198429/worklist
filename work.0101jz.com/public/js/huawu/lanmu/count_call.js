
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    //执行一个laydate实例
    // 开始日期
    laydate.render({
        elem: '.begin_date' //指定元素
        ,type: 'date'
        ,value: BEGIN_DATE// '2018-08-18' //必须遵循format参数设定的格式
        // ,min: get_now_format()//'2017-1-1'
        ,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });
    // 结束日期
    laydate.render({
        elem: '.end_date' //指定元素
        ,type: 'date'
        ,value: END_DATE// '2018-08-18' //必须遵循format参数设定的格式
        // ,min: get_now_format()//'2017-1-1'
        ,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });

    //切换提交
    $(document).on("click",".count_types_click",function(){
        var obj = $(this);
        var count_type = obj.data('count_type');
        console.log(count_type);
        // 获得兄弟姐妹
        obj.siblings().removeClass("on");
        obj.addClass("on");
        $('select[name=count_type]').val(count_type);
        $(".search_frm").click();
        return false;
    });

    //查询
    $('.search_frm').click(function(){
        console.log('点击查询');

        // 开始日期
        var begin_date = $('input[name=begin_date]').val();
        if(!judge_validate(4,'开始日期',begin_date,false,'date','','')){
            return false;
        }

        // 结束日期
        var end_date = $('input[name=end_date]').val();
        if(!judge_validate(4,'结束日期',end_date,false,'date','','')){
            return false;
        }

        if( end_date !== ''){
            if(begin_date == ''){
                layer_alert("请选择开始日期",3,0);
                return false;
            }
            if( !judge_validate(4,'结束日期必须',end_date,true,'data_size',begin_date,5)){
                return false;
            }
        }
        ajax_call_count(0, 0);//ajax工单统计
    });
    ajax_call_count(0, 0);//ajax工单统计
});

//ajax工单状态统计
function ajax_call_count(staff_id, operate_staff_id){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#search_frm").serialize();
    data['staff_id'] = staff_id;
    data['operate_staff_id'] = operate_staff_id;
    console.log(WORK_COUNT_URL);
    console.log(data);
    var layer_count_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : WORK_COUNT_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                //将原先div清空。
                $("#containerParent").html('<div class="tubiao" id="container" style="height: 100%;height: 500px"></div>');
                // var mychart3 = echarts.init(document.getElementById('dotubiaoPie'));
                // mychart3.clear();//只是清理画布，而不会删除 生成的元素节点
                // mychart3.setOption(option);
                // mychart3.on('click', function (params) {//绑定事件
                // });
                var result = ret.result;
                var title = result.title;// "特性示例：渐变色 阴影 点击缩放1";
                var dataAxis = result.dataAxis;// ['点', '击', '柱', '子', '或', '者', '两', '指', '在', '触', '屏', '上', '滑', '动', '能', '够', '自', '动', '缩', '放'];
                var data = result.dataY;// [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
                var yMax = result.yMax;// 500;
                var barGraphId = BAR_GRAPH_ID;// "container";
                /*
                * ymax: 柱状图阴影部分的高度(如果数值高于左边的数字，左边的数字也会增长)
                * dataAxis：柱状图上的显示的文字(一维数组)
                * data：柱状图上的数值(一维数组)
                * barGraphId：div的id名
                * */
                console.log(yMax);
                console.log(data);
                console.log(dataAxis);
                console.log(barGraphId);
                console.log(title);
                zhuzhuangtu(yMax,data,dataAxis,barGraphId,title);
                if($(".chart_title").length > 0){
                    $(".chart_title").html(title);
                }
                // 列表数据
                var listObj = $("#dataList");
                var htmlStr = resolve_baidu_template('baidu_template_data_list',ret.result,'');//解析
                listObj.html(htmlStr);
            }
            SUBMIT_FORM = true;//标记为未提交过
            layer.close(layer_count_index);//手动关闭
        }
    });
    return false;
}

(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td><%=item.count_date%><\/td>");
    document.write("        <td><%=item.amount%><\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();