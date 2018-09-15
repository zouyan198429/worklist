

const DYNAMIC_PAGE_BAIDU_TEMPLATE= "";//"baidu_template_data_page";//分页百度模板id
const DYNAMIC_TABLE = 'dynamic-table';//动态表格id
const DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
const DYNAMIC_TABLE_BODY = "data_list";//数据列表id
const DYNAMIC_LODING_BAIDU_TEMPLATE = "baidu_template_data_loding";//加载中百度模板id
const DYNAMIC_BAIDU_EMPTY_TEMPLATE = "baidu_template_data_empty";//没有数据记录百度模板id
const FRM_IDS = "search_frm";//需要读取的表单的id，多个用,号分隔
const SURE_FRM_IDS = "search_sure_form";//确认搜索条件需要读取的表单的id，多个用,号分隔
const PAGE_ID = "page";//当前页id
const PAGE_SIZE = Math.ceil(parseInt($('#pagesize').val()));;//每页显示数量
const TOTAL_ID = "total";//总记录数量[特别说明:小于0,需要从数据库重新获取]

$(function(){
    //读取第一页数据
    ajaxPageList(DYNAMIC_TABLE,DYNAMIC_PAGE_BAIDU_TEMPLATE,AJAX_URL,false,SURE_FRM_IDS,true,DYNAMIC_BAIDU_TEMPLATE,DYNAMIC_TABLE_BODY,DYNAMIC_LODING_BAIDU_TEMPLATE,DYNAMIC_BAIDU_EMPTY_TEMPLATE,PAGE_ID,PAGE_SIZE,TOTAL_ID);

    //查询
    $('.search_frm').click(function(){
        $("#"+PAGE_ID).val(1);//重归第一页
        //获得搜索表单的值
        append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
        reset_list(false);
    });
});


//重载列表
//is_read_page 是否读取当前页,否则为第一页 true:读取,false默认第一页
function reset_list(is_read_page){
    //重新读取数据
    ajaxPageList(DYNAMIC_TABLE,DYNAMIC_PAGE_BAIDU_TEMPLATE,AJAX_URL,is_read_page,SURE_FRM_IDS,true,DYNAMIC_BAIDU_TEMPLATE,DYNAMIC_TABLE_BODY,DYNAMIC_LODING_BAIDU_TEMPLATE,DYNAMIC_BAIDU_EMPTY_TEMPLATE,PAGE_ID,PAGE_SIZE,TOTAL_ID);
}

//删除 -> 确定按钮
//function del_sure(id){
//    sure_cancel_cancel();//隐藏弹出层显示对象
//    //ajax删除数据
//    operate_ajax('del',id);
// }
//批量删除
/*
function batch_del(){
    sure_cancel_cancel();//隐藏弹出层显示对象
    var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
    //ajax删除数据
    operate_ajax('batch_del',ids);
}
*/

//业务逻辑部分
var action = {
    add : function() {
        go(ADD_URL);
        return false;
    },
    show : function(id){
        // go(SHOW_URL + id);
        // location.href='/pms/Supplier/show?supplier_id='+id;
        var weburl = SHOW_URL + id;
        // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
        var tishi = SHOW_URL_TITLE;//"查看供应商";
        layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    edit : function(id){
        go(EDIT_URL + id);
        return false;
        //location.href='/pms/Supplier/modify?supplier_id='+id;
        //var weburl = '/pms/Supplier/modify?supplier_id='+id+"&operate_type=1";
        //var tishi = "修改供应商";
        //layeriframe(weburl,tishi,950,600,0);
        return false;
    },
    del : function(id){
        var index_query = layer.confirm('确定删除当前记录？删除后不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            operate_ajax('del',id);
            layer.close(index_query);
        }, function(){
        });
        return false;
        //if(false) {
        //   var sure_cancel_data = {
        //       'content':'确定删除当前记录？删除后不可恢复! ',//提示文字
        //       'sure_event':'del_sure('+id+');',//确定
        //   };
        //  sure_cancel_alert(sure_cancel_data);
        //  return false;
        //}
    },
    seledAll:function(obj){
        var checkAllObj =  $(obj);
        /*
        checkAllObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
        */
        checkAllObj.closest('#' + DYNAMIC_TABLE).find('.check_item').each(function(){
            if(!$(this).prop('disabled')){
                $(this).prop('checked', checkAllObj.prop('checked'));
            }
        });
    },
    seledSingle:function(obj) {// 单选点击
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
        checkObj.closest('#' + DYNAMIC_TABLE).find('.check_item').each(function () {
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
        checkObj.closest('#' + DYNAMIC_TABLE).find('.check_all').each(function () {
            $(this).prop('checked', allChecked);
        });

    },
    search:function(obj) {// 搜索
        var recordObj = $(obj);

        $("#"+PAGE_ID).val(1);//重归第一页
        //获得搜索表单的值
        append_sure_form(SURE_FRM_IDS,FRM_IDS);//把搜索表单值转换到可以查询用的表单中
        reset_list(false);
    },
    batchDel:function(obj) {// 批量删除
        var recordObj = $(obj);
        var index_query = layer.confirm('确定删除当前记录？删除后不可恢复!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var ids = get_list_checked(DYNAMIC_TABLE_BODY,1,1);
            //ajax删除数据
            operate_ajax('batch_del',ids);
            layer.close(index_query);
        }, function(){
        });
        return false;

    },
    exportExcel:function(obj) {// 导出EXCEL
        var recordObj = $(obj);
        go(EXPORT_EXCEL_URL);
        return false;
    },
    importExcel:function(obj) {// 导入EXCEL
        var recordObj = $(obj);
        go(IMPORT_EXCEL_URL);
        return false;
    },
};

//操作
function operate_ajax(operate_type,id){
    if(operate_type=='' || id==''){
        err_alert('请选择需要操作的数据');
        return false;
    }
    var operate_txt = "";
    var data ={};
    var ajax_url = "";
    switch(operate_type)
    {
        case 'del'://删除
            operate_txt = "删除";
            data = {'id':id}
            ajax_url = DEL_URL;// /pms/Supplier/ajax_del?operate_type=1
            break;
        case 'batch_del'://批量删除
            operate_txt = "批量删除";
            data = {'id':id}
            ajax_url = BATCH_DEL_URL;// "/pms/Supplier/ajax_del?operate_type=2";
            break;
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
                reset_list(true);
            }
            layer.close(layer_index)//手动关闭
        }
    });
}