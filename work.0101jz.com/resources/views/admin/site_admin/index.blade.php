@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 管理员管理</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			@include('common.pageParams')
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加管理员</a>
			</div>
		</div>
		{{--
		<div class="table-header">
			<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出EXCEL</button>
			<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入EXCEL</button>
		</div>
		--}}
		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>用户名</th>
				<th>真实姓名</th>
				<th>职位/角色</th>
				<th width=200 >操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			</tbody>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/admin/site_admin/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('admin/site_admin/add/0') }}"; //添加url
        var SHOW_URL = "{{url('admin/site_admin/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('admin/site_admin/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/admin/site_admin/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/admin/site_admin/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('admin/site_admin/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('admin/site_admin/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/admin/site_admin/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/admin/lanmu/site_admin.js') }}"  type="text/javascript"></script>
@endpush