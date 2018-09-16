
@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

    @include('common.pageParams')

	<div class="page">

		<div class="logo">
			<img src="http://ofn8u9rp0.bkt.clouddn.com/logo-ydapp3.png" alt="移动工单管理系统">
		</div>
		<div class="box" id="indmess">
			<ul>
				@foreach ($msgList as $msg)
				<li class="item">
					<div class="con">  <i class="fa fa-bell-o  fa-fw" aria-hidden="true"></i>  {{ $msg['mst_content'] or '' }}</div>
			 		<div class="btnbox2"><a href="#" class="btn smg_sure" data-id="{{ $msg['id'] or '' }}">收到</a></div>
			 		<div class="c"></div>
			 	</li>
				@endforeach
			</ul>

		</div>

		<div class="line10"></div>


		<div class="box" id="dynamic-table">
			<div class="tab">
                <a href="javascript:void(0);" class="on status_click" data-status="1">待确认({{ $waitSureCount or 0 }})</a>
				<a href="javascript:void(0);" class=" status_click"  data-status="2">待处理({{ $doingCount or 0 }})</a>
				<a href="javascript:void(0);" class=" status_click" data-status="4,8">已完成</a>

                <form style="display: none;" onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
                    <input type="text" name="status" value="">
                    <button class="btn btn-normal  search_frm ">搜索</button>
                </form>
			</div>
			<div class="bd"   id="data_list">


			</div>
            <div class=" bd mmfoot">
                <div class="mmfleft"></div>
                <div class=" mmfright pagination">
                </div>
            </div>
		</div>
		@include('mobile.layout_public.menu', ['menu_id' => 1])
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AJAX_URL = "{{ url('api/m/work/ajax_doing_list') }}";//ajax请求的url
        const ADD_URL = "{{ url('m/work/add/0') }}"; //添加url
        const SHOW_URL = "{{url('m/work/info/')}}/";//显示页面地址前缀 + id
        const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        const EDIT_URL = "{{url('m/work/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/m/work/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "{{ url('api/m/work/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('m/work/add/0') }}"; //"{{ url('api/m/work/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('m/work/add/0') }}"; //"{{ url('api/m/work/import') }}";//导入EXCEL地址

    const SURE_MSG_URL = "{{ url('api/m/msg/ajax_save') }}/";// ajax确认消息地址
    const SURE_WORK_URL = "{{ url('api/m/work/ajax_sure') }}/";// ajax确认工单地址
    const WIN_WORK_URL = "{{ url('api/m/work/ajax_win') }}/";// ajax工单结单地址


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
                operate_ajax('sure_msg',id);
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
                operate_ajax('sure_word',id);
                layer.close(index_query);
            }, function(){
            });
            return false;
        });

        // 确认结单
        $(document).on("click",".work_win",function(){
            var obj = $(this);
            var id = obj.data('id');
            var index_query = layer.confirm('确认结单？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                // operate_ajax('sure_word',id);
                // 跳转让到内容输入页
                alert(id);
                layer.close(index_query);
            }, function(){
            });
            return false;
        });
    });

    //操作
    function operate_ajax(operate_type,id){
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
</script>
 <script src="{{asset('js/common/list.js')}}"></script>

    <!-- 前端模板部分 -->
    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
    <script type="text/template"  id="baidu_template_data_list">
        <%for(var i = 0; i<data_list.length;i++){
        var item = data_list[i];
        var status = item.status;
        %>

        <div class="gd-list" >
            <div class="gd-hd">
                <p>
                    <span class="khname"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> <%=item.customer_name%>(<%=item.sex_text%>) </span>
                    <a href="tel:<%=item.call_number%>" class="btnnb fr" ><i class="fa fa-phone fa-fw" aria-hidden="true"></i> <%=item.call_number%></a>
            </div>
            <div class="gd-bd">
                <p><i class="fa fa-flag fa-fw" aria-hidden="true"></i>  工单类型：<%=item.type_name%>--<%=item.business_name%></p>
                <p class="khtip">{!!  $work['content'] or ''  !!}
                </p>
                <p>
                    <span class="gdtime"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 报修时间：<%=item.created_at%></span>
                    <span class="gdtime"> 预约时间：<%=item.book_time%></span>
                </p>
            </div>
            <div class="gd-fd">
                <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i> <%=item.city_name%><%=item.area_name%><%=item.address%> </p>
            </div>
            <div class="btnbox">
                <%if( status == 1){%>
                    <a href="javascript:void(0);" class="btn fr work_sure" data-id="<%=item.id%>" >确认</a>
                <%}%>
                <%if( status == 2){%>
                    <a href="javascript:void(0);" class="btn fr work_win" data-id="<%=item.id%>" >结单</a>
                <%}%>
                <div class="c"></div>
            </div>
        </div>
    <%}%>
</script>
<!-- 列表模板部分 结束-->
<!-- 前端模板结束 -->
@endpush



