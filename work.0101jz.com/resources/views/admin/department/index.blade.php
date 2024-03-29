@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 部门管理</div>
	<div class="mm">
        @if(isset($baseArr['account_type']) && $baseArr['account_type'] != 2)
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加部门</a>
			</div>
		</div>
        @endif

		<table id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>区县</th>
				<th>营业厅或片区</th>
				<th>排序</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			</tbody>
		</table>
		{{--
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pages">
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
			</div>
		</div>
		--}}

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/admin/department/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('admin/department/add/0') }}"; //添加url
        var SHOW_URL = "{{url('department/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('admin/department/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/admin/department/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/manage/department/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/department/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/department/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/department/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class
        var ACCOUNT_TYPE = {{ $baseArr['account_type'] or 1 }};// 帐号来源类型1本系统维护；2第三方系统同步；

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('/js/admin/lanmu/department.js') }}?1"  type="text/javascript"></script>
@endpush
