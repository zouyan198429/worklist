@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 业务时间</div>
	<div class="mm">
		@include('common.pageParams')
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加业务时间</a>
			</div>
		</div>
		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>名称</th>
				<th>时间秒数</th>
				<th>排序</th>
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
        const AJAX_URL = "{{ url('api/admin/service_time/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('admin/service_time/add/0') }}"; //添加url
        const SHOW_URL = "";// {{url('service_time/info/')}}/";//显示页面地址前缀 + id
        const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        const EDIT_URL = "{{url('admin/service_time/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/admin/service_time/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "";// {{ url('api/manage/service_time/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = ""; // {{ url('manage/service_time/add/0') }}"; //"{{ url('api/manage/service_time/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL =""; // "{{ url('manage/service_time/add/0') }}"; //"{{ url('api/manage/service_time/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
    <script src="{{ asset('/js/admin/lanmu/service_time.js') }}"  type="text/javascript"></script>
@endpush