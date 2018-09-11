@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 分数等级</div>
	<div class="mm">
		@include('common.pageParams')
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加分数等级</a>
			</div>
		</div>
		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>等级</th>
				<th>结束分数(<=)</th>
				<th width=200>操作</th>
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
        const AJAX_URL = "{{ url('api/admin/core_grade/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('admin/core_grade/add/0') }}"; //添加url
        const SHOW_URL = "";// {{url('core_grade/info/')}}/";//显示页面地址前缀 + id
        const EDIT_URL = "{{url('admin/core_grade/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/admin/core_grade/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "";// {{ url('api/manage/core_grade/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = ""; // {{ url('manage/core_grade/add/0') }}"; //"{{ url('api/manage/core_grade/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL =""; // "{{ url('manage/core_grade/add/0') }}"; //"{{ url('api/manage/core_grade/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script><script src="{{ asset('/js/admin/lanmu/core_grade.js') }}"  type="text/javascript"></script>
@endpush