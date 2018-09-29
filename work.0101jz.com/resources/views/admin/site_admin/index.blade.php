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
        const AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        const AJAX_URL = "{{ url('api/admin/site_admin/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('admin/site_admin/add/0') }}"; //添加url
        const SHOW_URL = "{{url('admin/site_admin/info/')}}/";//显示页面地址前缀 + id
        const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        const EDIT_URL = "{{url('admin/site_admin/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/admin/site_admin/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "{{ url('api/admin/site_admin/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('admin/site_admin/add/0') }}"; //"{{ url('api/admin/site_admin/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('admin/site_admin/add/0') }}"; //"{{ url('api/admin/site_admin/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/admin/lanmu/site_admin.js') }}"  type="text/javascript"></script>
@endpush