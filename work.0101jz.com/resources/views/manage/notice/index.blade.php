@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 通知公告</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on " onclick="action.add()" >添加通知公告</a>
			</div>

			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<div class="msearch fr">
				{{--
				<select class="wmini">
					<option value="a01">全部</option>
					<option value="a02">维修部</option>
					<option value="a03">话务部</option>
					<option value="a04">行政部</option>
				</select>
				--}}
				<input type="text" name="title" value=""  placeholder="请输入标题" />
				<button class="btn btn-normal search_frm">搜索</button>
			</div>
			</form>
		</div>
		<table id="dynamic-table" class="table2">
			<thead>
			<tr>
				<th>标题</th>
				<th >阅读量</th>
				<th >添加人</th>
				<th width="180px">添加时间</th>
				<th width="250px">操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
				{{--
				<tr>
					<td><input type="checkbox" name="vehicle" value="11" /></td>
					<td class="tl" ><a href="{{ url('manage/lore/info') }}" >企业品牌推广的战略选择</a></td>
					<td>★★</td>
					<td>话务</td>
					<td>张兰兰</td>
					<td>2015-04-22</td>
					<td>233</td>
					<td><a href="{{ url('manage/lore/add') }}" class="btn" >编辑</a></td>
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
        var AJAX_URL = "{{ url('api/manage/notice/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('manage/notice/add/0') }}"; //添加url
        var SHOW_URL = "{{url('manage/notice/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "通知公告详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 2 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('manage/notice/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/manage/notice/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/manage/notice/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/notice/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/notice/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/notice/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/manage/lanmu/notice.js') }}"  type="text/javascript"></script>
@endpush