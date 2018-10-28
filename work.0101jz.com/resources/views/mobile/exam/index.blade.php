@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">在线考试</div>
		</div>
		@include('common.pageParams')
		<section class="main" id="study" >
			<div class="hd tab">
				<a href="{{ url('m/exam') }}" class="on">近期考试</a>
				<a href="{{ url('m/exam_score') }}">成绩查询</a>
			</div>
			<div class="bd"  id="dynamic-table">
				<ul class="boxlist"  id="data_list">

				</ul>
			</div>

		</section>
		@include('mobile.layout_public.menu', ['menu_id' => 3])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/m/exam/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('m/exam/add/0') }}"; //添加url
        var SHOW_URL = "{{url('m/exam/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "通知公告详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 2 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('m/exam/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/m/exam/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/m/exam/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('m/exam/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('m/exam/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/m/exam/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

        var DYNAMIC_LODING_BAIDU_TEMPLATE = "baidu_template_data_loding_li";//加载中百度模板id
        var DYNAMIC_BAIDU_EMPTY_TEMPLATE = "baidu_template_data_empty_li";//没有数据记录百度模板id
	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	{{--<script src="{{ asset('js/m/lanmu/exam.js') }}"  type="text/javascript"></script>--}}
	<!-- 前端模板部分 -->
	<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
	<script type="text/template"  id="baidu_template_data_list">
		<%for(var i = 0; i<data_list.length;i++){
		var item = data_list[i];
		can_modify = true;
		%>
		<li>

			<h3><%=item.exam_num%></h3>
			<dl>
				<dt>开考日期</dt>
				<dd><%=item.exam_begin_time%></dd>
			</dl>
			<dl>
				<dt>考试时长</dt>
				<dd><%=item.exam_minute%>分钟</dd>
			</dl>
			<dl>
				<dt>考试主题</dt>
				<dd><%=item.exam_subject%></dd>
			</dl>
			<dl>
				<dt>状态</dt>
				<dd><%=item.status_text%></dd>
			</dl>
			<div class="c"></div>
			<div class="btnbox">
				<button >
					<i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 距离开考：2天3小时33分钟
				</button>
			</div>
		</li>
    <%}%>
</script>
<!-- 列表模板部分 结束-->
<!-- 前端模板结束 -->
@endpush