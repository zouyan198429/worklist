
$(function(){
    $('.search_frm').trigger("click");// 触发搜索事件
    // reset_list_self(false, false);
});
//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
// ajax_async ajax 同步/导步执行 //false:同步;true:异步  需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
function reset_list_self(is_read_page, ajax_async){
    console.log('is_read_page', typeof(is_read_page));
    console.log('ajax_async', typeof(ajax_async));
    reset_list(is_read_page, false);
    initList();
}
// 初始化
function initList(){
    // 获得选中的员工id 数组
    var SELECTED_IDS = parent.getSelectedStaffIds();
    console.log('SELECTED_IDS',SELECTED_IDS);
    $('#data_list').find('tr').each(function () {
        var trObj = $(this);
        // console.log(trObj.html());
        var checkedObj = trObj.find('.check_item');
        console.log('checkedObj', checkedObj.length);
        var staff_id = checkedObj.val();
        console.log('staff_id', staff_id);
        if(SELECTED_IDS.indexOf(staff_id) !== -1){// 已选
            trObj.find('.add').hide();
            trObj.find('.del').show();
            checkedObj.prop('disabled',true);
            checkedObj.prop('checked',false);
        }else{// 未选
            trObj.find('.add').show();
            trObj.find('.del').hide();
            checkedObj.prop('disabled',false);
        }

    });
}

//业务逻辑部分
var otheraction = {
    addBatch : function(obj){// 批量增加员工
        // var checkAllObj =  $(obj);
        var index_query = layer.confirm('确定增加选中记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            if(ids == '') err_alert('请选择需要操作的记录');
            parent.addStaff(ids);
            initList();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    addBatchSearch : function(obj){// 批量增加员工- 查询条件
        // var checkAllObj =  $(obj);
        var index_query = layer.confirm('确定增加记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            if(!$('#dynamic-table').find('.check_all').is(':checked')) {
                $('#dynamic-table').find('.check_all').trigger("click");
            }// 触发搜索事件
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            if(ids == '') err_alert('请选择需要操作的记录');
            parent.addStaff(ids);
            initList();
            layer.close(index_query);
            //获得搜索表单的值
            // append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
            // $('.search_frm').trigger("click");// 触发搜索事件
            // //获得表单各name的值
            // var data = get_frm_values(SURE_FRM_IDS);// {}
            // console.log(data);
            // var layer_index = layer.load();
            // $.ajax({
            //     'async': false,// true,//false:同步;true:异步
            //     'type' : 'POST',
            //     'url' : AJAX_SEARCH_IDS_URL,
            //     'data' : data,
            //     'dataType' : 'json',
            //     'success' : function(ret){
            //         console.log('ret',ret);
            //         if(!ret.apistatus){//失败
            //             //alert('失败');
            //             err_alert(ret.errorMsg);
            //         }else{//成功
            //             var ids = ret.result;
            //             console.log('ids', ids);
            //             //var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //             if(ids == '') err_alert('请选择需要操作的记录');
            //             parent.addStaff(ids);
            //             initList();
            //         }
            //         layer.close(layer_index)//手动关闭
            //     }
            // });
            // layer.close(index_query);
        }, function(){
        });
        return false;
    },
    add : function(id){// 增加单个试题
        var index_query = layer.confirm('确定增加当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            parent.addStaff(id + '');
            initList();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
    del : function(id){// 取消
        var index_query = layer.confirm('确定取消当前记录？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            parent.removeStaff(id);
            initList();
            layer.close(index_query);
        }, function(){
        });
        return false;
    },
};

(function() {
    document.write("");
    document.write("    <!-- 前端模板部分 -->");
    document.write("    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->");
    document.write("    <script type=\"text\/template\"  id=\"baidu_template_data_list\">");
    document.write("");
    document.write("        <%for(var i = 0; i<data_list.length;i++){");
    document.write("        var item = data_list[i];");
    //document.write("        var can_modify = false;");
    //document.write("        if( item.issuper==0 ){");
    document.write("        can_modify = true;");
   // document.write("        }");
    document.write("        %>");
    document.write("");
    document.write("        <tr>");
    document.write("            <td>");
    document.write("                <label class=\"pos-rel\">");
    document.write("                    <input  onclick=\"action.seledSingle(this)\" type=\"checkbox\" class=\"ace check_item\" <%if( false &&  !can_modify){%> disabled <%}%>  value=\"<%=item.id%>\"\/>");
    document.write("                  <span class=\"lbl\"><\/span>");
    document.write("                <\/label>");
    document.write("            <\/td>");
    document.write("            <td><%=item.work_num%><\/td>");
    document.write("            <td><%=item.department_name%>/<%=item.group_name%><\/td>");
    document.write("            <td><%=item.real_name%><\/td>");
    document.write("            <td><%=item.sex_text%><\/td>");
    document.write("            <td><%=item.position_name%><\/td>");
    // document.write("            <td><%=item.tel%><\/td>");
    document.write("            <td><%=item.mobile%><\/td>");
    // document.write("            <td><%=item.qq_number%><\/td>");
    document.write("            <td>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info add \" onclick=\"otheraction.add(<%=item.id%>)\">");
    document.write("                <i class=\"ace-icon fa fa-plus bigger-60\"> 选择<\/i>");
    document.write("            <\/a>");
    document.write("            <a href=\"javascript:void(0);\" class=\"btn btn-mini btn-info del pink \" onclick=\"otheraction.del(<%=item.id%>)\">");
    document.write("               <i class=\"ace-icon fa fa-trash-o bigger-60\"> 取消<\/i>");
    document.write("            <\/a>");
    document.write("");
    document.write("            <\/td>");
    document.write("        <\/tr>");
    document.write("    <%}%>");
    document.write("<\/script>");
    document.write("<!-- 列表模板部分 结束-->");
    document.write("<!-- 前端模板结束 -->");
}).call();