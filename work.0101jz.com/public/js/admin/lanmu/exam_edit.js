
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    //执行一个laydate实例
    // 开始日期
    laydate.render({
        elem: '.exam_begin_time' //指定元素
        ,type: 'datetime'
        ,value: BEGIN_TIME// '2018-08-18' //必须遵循format参数设定的格式
        ,min: get_now_format()//'2017-1-1'
        //,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });

    // 最晚开始日期
    laydate.render({
        elem: '.exam_begin_time_last' //指定元素
        ,type: 'datetime'
        ,value: BEGIN_TIME_LAST// '2018-08-18' //必须遵循format参数设定的格式
        ,min: get_now_format()//'2017-1-1'
        //,max: get_now_format()//'2017-12-31'
        ,calendar: true//是否显示公历节日
    });

    //提交
    $(document).on("click","#submitBtn",function(){
        //var index_query = layer.confirm('您确定提交保存吗？', {
        //    btn: ['确定','取消'] //按钮
        //}, function(){
        ajax_form();
        //    layer.close(index_query);
        // }, function(){
        //});
        return false;
    });
    initStaff(ID_VAL);// 页面初始化员工
});
//ajax提交表单
function ajax_form(){
    if (!SUBMIT_FORM) return false;//false，则返回

    // 验证信息
    var id = $('input[name=id]').val();
    if(!judge_validate(4,'记录id',id,true,'digit','','')){
        return false;
    }

    // 场次
    // var exam_num = $('input[name=exam_num]').val();
    // if(!judge_validate(4,'场次',exam_num,true,'length',2,50)){
    //     return false;
    // }

    // 考试主题
    var exam_subject = $('input[name=exam_subject]').val();
    if(!judge_validate(4,'考试主题',exam_subject,true,'length',2,100)){
        return false;
    }

    // 开考时间
    var begin_date = $('input[name=exam_begin_time]').val();
    if(!judge_validate(4,'开考时间',begin_date,true,'date','','')){
        return false;
    }

    // 最晚开考时间
    var end_date = $('input[name=exam_begin_time_last]').val();
    if(!judge_validate(4,'最晚开考时间',end_date,true,'date','','')){
        return false;
    }

    if( end_date !== ''){
        if(begin_date == ''){
            layer_alert("请选择开考时间",3,0);
            return false;
        }
        if( !judge_validate(4,'最晚开考时间必须',end_date,true,'data_size',begin_date,5)){
            return false;
        }
    }

    var now_time = get_now_format();
    if( !judge_validate(4,'开考时间必须',begin_date,true,'data_size',now_time,5)){
        return false;
    }

    // 考试时长(分)
    var exam_minute = $('input[name=exam_minute]').val();
    if(!judge_validate(4,'考试时长',exam_minute,true,'positive_int','','')){
        return false;
    }

    // 试卷
    var paper_id = $('input[name=paper_id]').val();
    var judge_seled = judge_validate(1,'试卷',paper_id,true,'positive_int','','');
    if(judge_seled != ''){
        layer_alert("请选择试卷",3,0);
        return false;
    }

    var paper_history_id = $('input[name=paper_history_id]').val();
    var judge_seled = judge_validate(1,'试卷',paper_history_id,true,'positive_int','','');
    if(judge_seled != ''){
        layer_alert("请选择试卷",3,0);
        return false;
    }

    // 及格分数
    var pass_score = $('input[name=pass_score]').val();
    if(!judge_validate(4,'及格分数',pass_score,true,'doublepositive','','')){
        return false;
    }


    // 判断考试员工
    var staff_num = $('.staff_td').find('.data_list').find('tr').length;
    if(staff_num <= 0){
        layer_alert("请选择参加考试的员工",3,0);
        return false;
    }

    // 验证通过
    SUBMIT_FORM = false;//标记为已经提交过
    var data = $("#addForm").serialize();
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
            if(!ret.apistatus){//失败
                SUBMIT_FORM = true;//标记为未提交过
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                go(LIST_URL);
                // var supplier_id = ret.result['supplier_id'];
                //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                //    SUPPLIER_ID_VAL = supplier_id;
                //    $('input[name="supplier_id"]').val(supplier_id);
                //}
                // save_success();
            }
            layer.close(layer_index)//手动关闭
        }
    });
    return false;
};

//业务逻辑部分
var otheraction = {
    selectPaper: function(obj){// 选择试卷
        var recordObj = $(obj);
        //获得表单各name的值
        var weburl = SELECT_PAPER_URL;
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = '选择试卷';//"查看供应商";
        console.log('weburl', weburl);
        layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    updatePaper : function(obj){// 试卷更新
        var recordObj = $(obj);
        var index_query = layer.confirm('确定更新当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var paper_id = $('input[name=paper_id]').val();
            addPaper(paper_id);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    selectStaff: function(obj){// 选择员工
        var recordObj = $(obj);
        //获得表单各name的值
        var weburl = SELECT_STAFF_URL;
        console.log(weburl);
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        // var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = '选择员工';//"查看供应商";
        console.log('weburl', weburl);
        layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    edit : function(obj, parentTag, staff_id){// 更新员工
        var recordObj = $(obj);
        var index_query = layer.confirm('确定更新当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var trObj = recordObj.closest(parentTag);// 'tr'
            updateStaff(staff_id,trObj);
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    del : function(obj, parentTag){// 删除
        var recordObj = $(obj);
        var index_query = layer.confirm('确定移除当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var trObj = recordObj.closest(parentTag);// 'tr'
            trObj.remove();
            autoCountStaffNum();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    batchDel:function(obj, parentTag, delTag) {// 批量删除
        var recordObj = $(obj);
        var index_query = layer.confirm('确定移除选中记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var hasDel = false;
            recordObj.closest(parentTag).find('.check_item').each(function () {
                if (!$(this).prop('disabled') && $(this).val() != '' &&  $(this).prop('checked') ) {
                    // $(this).prop('checked', checkAllObj.prop('checked'));
                    var trObj = $(this).closest(delTag);// 'tr'
                    trObj.remove();
                    hasDel = true;
                }
            });
            if(!hasDel){
                err_alert('请选择需要操作的数据');
            }
            autoCountStaffNum();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    moveUp : function(obj, parentTag){// 上移
        var recordObj = $(obj);
        var current = recordObj.closest(parentTag);//获取当前<tr>  'tr'
        var prev = current.prev();  //获取当前<tr>前一个元素
        console.log('index', current.index());
        if (current.index() > 0) {
            current.insertBefore(prev); //插入到当前<tr>前一个元素前
        }else{
            layer_alert("已经是第一个，不能移动了。",3,0);
        }
        return false;
    },
    moveDown : function(obj, parentTag){// 下移
        var recordObj = $(obj);
        var current = recordObj.closest(parentTag);//获取当前<tr>'tr'
        var next = current.next(); //获取当前<tr>后面一个元素
        console.log('length', next.length);
        console.log('next', next);
        if (next.length > 0 && next) {
            current.insertAfter(next);  //插入到当前<tr>后面一个元素后面
        }else{
            layer_alert("已经是最后一个，不能移动了。",3,0);
        }
        return false;
    },
    seledAll:function(obj, parentTag){
        var checkAllObj =  $(obj);
        /*
        checkAllObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
        */
        checkAllObj.closest(parentTag).find('.check_item').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
    },
    seledSingle:function(obj, parentTag) {// 单选点击
        var checkObj = $(obj);
        var allChecked = true;
        /*
         checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
            if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                // $(this).prop('checked', checkAllObj.prop('checked'));
                allChecked = false;
                return false;
            }
        });
        */
        checkObj.closest(parentTag).find('.check_item').each(function () {
            if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                // $(this).prop('checked', checkAllObj.prop('checked'));
                allChecked = false;
                return false;
            }
        });
        // 全选复选操选中/取消选中
        /*
        checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
            if (!$(this).prop('disabled') && $(this).val() == ''  ) {
                $(this).prop('checked', allChecked);
                return false;
            }
        });
        */
        checkObj.closest(parentTag).find('.check_all').each(function () {
            $(this).prop('checked', allChecked);
        });

    },
};

// 页面初始化员工
// id 考试id
function initStaff(id) {
    if(id <= 0) return ;
    var data = {};
    data['exam_id'] = id;
    var layer_index = layer.load();
    console.log(data);
    console.log(AJAX_STAFF_URL);
    $.ajax({
        'type' : 'POST',
        'url' : AJAX_STAFF_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var staff_list = ret.result;
                console.log('staff_list', staff_list);
                // 解析数据
                initAnswer('staff_td', staff_list, 1);
                autoCountStaffNum();
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

// 更新试题
// id 试题id
// trObj tr对象
function updateStaff(id, trObj) {
    if(id <= 0) return ;
    var data = {};
    data['id'] = id;
    var layer_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : AJAX_UPDATE_STAFF_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var subject_list = ret.result;
                console.log('subject_list', subject_list);
                var data_list = {
                    'data_list': subject_list,
                };
                // 解析数据
                var htmlStr = initAnswer('', data_list, 3);
                trObj.after(htmlStr);
                trObj.remove();
                autoCountStaffNum();
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
        $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).html(htmlStr);
    }else if(type == 2){
        $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).append(htmlStr);
    }
}

// 获得参考人员数量
function autoCountStaffNum(){
    var total = 0;
    $('.staff_td').each(function () {
        var departmentObj = $(this);
        var staff_num = departmentObj.find('.data_list').find('tr').length;
        console.log('staff_num',staff_num);
        departmentObj.find('input[name="staff_num[]"]').val(staff_num);
        departmentObj.find('.staff_num').html(staff_num);
        total += parseInt(staff_num);
    });
    $('.subject_num').html(total);

}

// 获得选中的试卷id 数组
function getSelectedPaperIds(){
    var paper_ids = [];
    var paper_id = $('input[name=paper_id]').val();
    paper_ids.push(paper_id);
    console.log('paper_ids' , paper_ids);
    return paper_ids;
}

// 取消
// paper_id 试卷id
function removePaper(paper_id){
    var seled_paper_id = $('input[name=paper_id]').val();
    if(paper_id == seled_paper_id){
        $('input[name=paper_id]').val('');
        $('input[name=paper_history_id]').val('');
        $('.paper_name').html('');
        $('.update_paper').hide();
    }
}

// 增加
// paper_id 试题id, 多个用,号分隔
function addPaper(paper_id){
    if(paper_id == '') return ;
    var data = {};
    data['id'] = paper_id;
    console.log('data', data);
    console.log('AJAX_PAPER_ADD_URL', AJAX_PAPER_ADD_URL);
    var layer_index = layer.load();
    $.ajax({
        'async': false,// true,//false:同步;true:异步
        'type' : 'POST',
        'url' : AJAX_PAPER_ADD_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var paper_info = ret.result;
                console.log('paper_info', paper_info);
                $('input[name=paper_id]').val(paper_info.paper_id);
                $('input[name=paper_history_id]').val(paper_info.paper_history_id);
                $('.paper_name').html(paper_info.paper_name);
                var now_paper = paper_info.now_paper;// 最新的试题 0没有变化 ;1 已经删除  2 试卷不同
                if(now_paper == 2 ){
                    $('.update_paper').show();
                }else{
                    $('.update_paper').hide();
                }
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

// 获得员工id 数组
function getSelectedStaffIds(){
    var staff_ids = [];
    $('.staff_td').find('.data_list').find('input[name="staff_ids[]"]').each(function () {
        var staff_id = $(this).val();
        staff_ids.push(staff_id);
    });
    console.log('staff_ids' , staff_ids);
    return staff_ids;
}

// 取消
// staff_id 试题id
function removeStaff(staff_id){
    $('.staff_td').find('.data_list').find('input[name="staff_ids[]"]').each(function () {

        var tem_staff_id = $(this).val();
        if(staff_id == tem_staff_id){
            $(this).closest('tr').remove();
            return ;
        }
    });
    autoCountStaffNum();
}

// 增加
// staff_id 试题id, 多个用,号分隔
function addStaff( staff_id){
    console.log('addStaff', staff_id);
    if(staff_id == '') return ;
    // 去掉已经存在的记录id
    var selected_ids = getSelectedStaffIds();
    var staff_id_arr = staff_id.split(",");
    //差集
    var diff_arr = staff_id_arr.filter(function(v){ return selected_ids.indexOf(v) == -1 });
    staff_id = diff_arr.join(',');
    if(staff_id == '') return ;

    var data = {};
    data['id'] = staff_id;
    var layer_index = layer.load();
    $.ajax({
        'async': false,// true,//false:同步;true:异步
        'type' : 'POST',
        'url' : AJAX_STAFF_ADD_URL,
        'data' : data,
        'dataType' : 'json',
        'success' : function(ret){
            console.log('ret',ret);
            if(!ret.apistatus){//失败
                //alert('失败');
                err_alert(ret.errorMsg);
            }else{//成功
                var staff_list = ret.result;
                console.log('staff_list', staff_list);
                var data_list = {
                    'data_list': staff_list,
                };
                // 解析数据
                initAnswer('staff_td', data_list, 2);
                autoCountStaffNum();
            }
            layer.close(layer_index)//手动关闭
        }
    });
}

(function() {
    document.write("<!-- 前端模板部分 -->");
    document.write("<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("<script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("    <%for(var i = 0; i<data_list.length;i++){");
    document.write("    var item = data_list[i];");
    document.write("    var now_staff = item.now_staff;");
    document.write("    can_modify = true;");
    document.write("    %>");
    document.write("    <tr>");
    document.write("        <td>");
    document.write("            <label class=\"pos-rel\">");
    document.write("                <input onclick=\"otheraction.seledSingle(this , \'.table2\')\" type=\"checkbox\" class=\"ace check_item\" value=\"<%=item.staff_id%>\">");
    document.write("                <span class=\"lbl\"><\/span>");
    document.write("            <\/label>");
    document.write("            <input type=\"hidden\" name=\"staff_ids[]\" value=\"<%=item.staff_id%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"staff_history_ids[]\" value=\"<%=item.staff_history_id%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"department_ids[]\" value=\"<%=item.department_id%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"department_names[]\" value=\"<%=item.department_name%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"group_ids[]\" value=\"<%=item.group_id%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"group_names[]\" value=\"<%=item.group_name%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"position_ids[]\" value=\"<%=item.position_id%>\"\/>");
    document.write("            <input type=\"hidden\" name=\"position_names[]\" value=\"<%=item.position_name%>\"\/>");
    document.write("        <\/td>");
    document.write("        <td><%=item.work_num%><\/td>");
    document.write("        <td><%=item.department_name%>\/<%=item.group_name%><\/td>");
    document.write("        <td><%=item.real_name%><\/td>");
    document.write("        <td><%=item.sex_text%><\/td>");
    document.write("        <td><%=item.position_name%><\/td>");
    document.write("        <td><%=item.mobile%><\/td>");
    document.write("        <td>");
    document.write("            <%if( now_staff == 2 || now_staff == 4){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.edit(this, \'tr\', <%=item.staff_id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-pencil bigger-60 pink\"> 更新[员工已更新]<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("            <%if( now_staff == 1){%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.del(this, \'tr\')\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60 wrong\"> 删除[员工已删]<\/i>");
    document.write("            <\/a>");
    document.write("            <%}%>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info\" onclick=\"otheraction.del(this, \'tr\')\">");
    document.write("                <i class=\"ace-icon fa fa-trash-o bigger-60\"> 移除<\/i>");
    document.write("            <\/a>");
    document.write("        <\/td>");
    document.write("    <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();
