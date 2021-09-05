@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 通知公告</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<div class="tabbox" >
			</div>

			<div class="msearch fr">
				<input type="text"  name="title" value=""  placeholder="请输入标题"    />
				<button class="btn btn-normal   search_frm">搜索</button>
			</div>
			</form>
		</div>
		<table id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>标题</th>
				<th  width="180px">上传时间</th>
			</tr>
			</thead>
			<tbody id="data_list">
			{{--
			<tr>
				<td class="tl" ><a href="{{ url('weixiu/notice/info') }}" ><i class="fa fa-angle-right fa-fw" aria-hidden="true"></i> 企业品牌推广的战略选择</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			--}}
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="pagination">
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/weixiu/notice/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('weixiu/notice/add/0') }}"; //添加url
        var SHOW_URL = "{{url('weixiu/notice/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "通知公告详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('weixiu/notice/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/weixiu/notice/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/weixiu/notice/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('weixiu/notice/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('weixiu/notice/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/weixiu/notice/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/weixiu/lanmu/notice.js') }}"  type="text/javascript"></script>
@endpush