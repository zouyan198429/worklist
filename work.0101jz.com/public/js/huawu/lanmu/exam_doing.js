
$(function(){
    //上一题
    $(document).on("click",".pre_subject",function(){
        //var index_query = layer.confirm('您确定提交保存吗？', {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        getSubject(EXAM_STAFF_ID, 1);
        //    layer.close(index_query);
        // }, function(){
        //});
        return false;
    });
    //下一题
    $(document).on("click",".next_subject",function(){
        //var index_query = layer.confirm('您确定提交保存吗？', {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        getSubject(EXAM_STAFF_ID, 2);
        //    layer.close(index_query);
        // }, function(){
        //});
        return false;
    });
    //交卷
    $(document).on("click",".finish",function(){
        var index_query = layer.confirm('您确定交卷吗？交卷后将不能答案！', {
            btn: ['确定','取消'] //按钮
        }, function(){
            getSubject(EXAM_STAFF_ID, 4);
            layer.close(index_query);
        }, function(){
        });
        return false;
    });
    // 初始在线考试
    initExam();
});

// 初始在线考试
function initExam(){
    if (!SUBMIT_FORM) return false;//false，则返回
    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = {};// $("#addForm").serialize();
    data['id'] = EXAM_STAFF_ID;
    console.log(INIT_EXAM_URL);
    console.log(data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : INIT_EXAM_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            SUBMIT_FORM = true;//标记为未提交过
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                getSubject(EXAM_STAFF_ID, 3);
            }
            layer.close(layer_index)//手动关闭
        }
    });
}
// 上一题/下一题请求数据
// exam_staff_id
// submit_type 1上一题,2下一题，3其它：初始首题或前面处理的题
function getSubject(exam_staff_id, submit_type){
    if (!SUBMIT_FORM) return false;//false，则返回
    console.log('exam_staff_id', exam_staff_id);
    console.log('submit_type', submit_type);
    var subject_history_id = $('input[name=subject_history_id]').val();
    if(false && subject_history_id > 0){
        // 题目类型1单选；2多选；4判断
        var subject_type = $('input[name=subject_type]').val();

        // 判断题-
        if(subject_type == 4){
            var answer = $('input[name=answer]:checked').val() || '';
            var judge_seled = judge_validate(1,'答案',answer,true,'custom',/^[01]$/,"");
            if(judge_seled != ''){
                layer_alert("请选择答案",3,0);
                //err_alert('<font color="#000000">' + judge_seled + '</font>');
                return false;
            }

        }else{// 单选/多选
            var trsObj = $('#data_list').find('li');// find('tr');
            if(trsObj.length <= 0){
                layer_alert("没有答案选项，请联系管理人员!",3,0);
                return false;
            }
            var has_answer = false;
            trsObj.each(function(){
                var trObj = $(this);

                switch(subject_type)
                {
                    case 1://1单选；
                    case '1':
                        console.log('1单选');
                        if(trObj.find('input[name="answer_val"]').is(':checked')){
                            has_answer = true;
                        }
                        break;
                    case 2://2多选
                    case '2':
                        console.log('2多选');
                        if(trObj.find('input[name="check_answer_val[]"]').is(':checked')){
                            has_answer = true;
                        }
                        break;
                    default:
                        console.log('其它' + subject_type);
                        break;
                }
            });
            // 没有选择答案
            if(!has_answer){
                layer_alert("请选择答案",3,0);
                return false;
            }

        }

    }
    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
    if(data != '') data += '&' ;
    data += 'submit_type=' + submit_type ;
    console.log(SAVE_URL);
    console.log(data);
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SAVE_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log(ret);
            SUBMIT_FORM = true;//标记为未提交过
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var answer_info = ret.result;
                if(answer_info.win == 1){// 交卷成功
                    go(WIN_URL);
                    return '';
                }
                $('.subject_num').html(answer_info.subject_num);
                $('.subject_type_text').html(answer_info.type_text);
                $('.subject_title').html(answer_info.title);
                // 解析数据
                initAnswer('content', answer_info, 1);
                var subject_type = answer_info.subject_type;//  1单选；2多选；4判断
                var answer_user = answer_info.answer_user;
                var subject_num = answer_info.subject_num;
                var subject_count = answer_info.subject_count;
                $('input[name=subject_type]').val(subject_type);
                $('input[name=subject_id]').val(answer_info.subject_id);
                $('input[name=subject_history_id]').val(answer_info.subject_history_id);
                $('.doing_num').html(answer_info.subject_num);
                $('.count_num').html(answer_info.subject_count);

                if(subject_type == '4'){
                    $('#answer_judge').show();
                    $('.answer_many').hide();
                    $('input[type=radio][name="answer"]:checked').prop("checked", false);
                    $("input[type=radio][name='answer'][value='" + answer_user + "']").prop("checked",true);  //根据Value值设置Radio为选中状态
                }else{
                    $('#answer_judge').hide();
                    $('.answer_many').show();
                }

                var key = 'A'.charCodeAt();
                console.log('key');
                var val = 1;
                $('.answer_many').find('li').each(function () {
                    var trObj = $(this);
                    var colum = String.fromCharCode(key);
                    console.log('colum',colum );
                    trObj.find('.colum').html(colum);
                    // trObj.find('input[name=answer_val]').val(val);
                    // trObj.find('.check_answer').val(val);
                    switch(subject_type)
                    {
                        case 1://1单选；
                        case '1':
                            console.log('1单选');
                            trObj.find('input[name=answer_val]').show();
                            trObj.find('.check_answer').hide();
                            if((answer_user & val ) == val){
                                trObj.find('input[name=answer_val]').attr('checked', true);
                            }
                            break;
                        case 2://2多选
                        case '2':
                            console.log('2多选');
                            trObj.find('input[name=answer_val]').hide();
                            trObj.find('.check_answer').show();
                            if((answer_user & val ) == val){
                                trObj.find('.check_answer').attr('checked', true);
                            }
                            break;
                        default:
                            console.log('其它' + subject_type);
                            break;
                    }
                    key++;
                    val *= 2;
                });
                // 上一页按钮
                $('.pre_subject').show();
                if(subject_num == 1){
                    $('.pre_subject').hide();
                }
                // 下一页按钮
                $('.next_subject').show();
                if(subject_num == subject_count){
                    $('.next_subject').hide();
                }
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

// 初始化答案列表
// data_list 数据对象 {'data_list':[{}]}
// type类型 1 全替换 2 追加到后面 3 返回html
function initAnswer(class_name, data_list, type){
    var htmlStr = resolve_baidu_template(DYNAMIC_BAIDU_TEMPLATE,data_list,'');//解析
    if(type == 3) return htmlStr;
    //alert(htmlStr);
    //alert(body_data_id);
    if(type == 1){
        $('.'+ class_name).find('#' + DYNAMIC_TABLE_BODY).html(htmlStr);
    }else if(type == 2){
        $('.'+ class_name).find('#' + DYNAMIC_TABLE_BODY).append(htmlStr);
    }
}

function countTime() {
    var trObj = $('.mmhead');
    // var date = new Date();
    // var now = date.getTime();
    // var endDate = new Date("2018-10-30 00:00:00");//设置截止时间
    // var end = endDate.getTime();
    // var leftTime = end - now; //时间差

    // var exam_begin_time = trObj.data('exam_begin_time');// 开始时间
    // var beginDate = new Date(exam_begin_time);//设置开始时间
    // var begin = beginDate.getTime();

    var date = new Date();
    var now = date.getTime();

    var exam_end_time = EXAM_END_TIME;// 结束时间
    var endDate = new Date(exam_end_time);//设置截止时间
    var end = endDate.getTime();
    var leftTime = end - now; //时间差
    var d, h, m, s, ms;
    if (leftTime >= 0) {
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
    }else{
        trObj.find('.back_time').html('考试结束!');
    }
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
    document.write("    var now_staff = item.now_staff;");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("");
    document.write("    <li>");
    document.write("        <label>");
    document.write("            <input type=\"radio\" name=\"answer_val\" value=\"<%=item.answer_val%>\"  \/>");
    document.write("            <input type=\"checkbox\" class=\"check_answer\"  name=\"check_answer_val[]\" value=\"<%=item.answer_val%>\"  \/>");
    document.write("            <span class=\"colum\"><%=item.colum%><\/span>、");
    document.write("            <span><%=item.answer_content%><\/span>");
    document.write("        <\/label>");
    document.write("    <\/li>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();