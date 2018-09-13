@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			@include('common.pageParams')
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<div class="tabbox" >
				<select class="wnormal" name="department_id">
					<option value="">全部</option>
					@foreach ($department_kv as $k=>$txt)
						<option value="{{ $k }}"  @if(isset($department_id) && $department_id == $k) selected @endif >{{ $txt }}</option>
					@endforeach
				</select>
			</div>

			<div class="msearch fr"> <input type="text" value=""  name="keyword"  /> <button class="btn btn-normal  search_frm" >搜索</button> </div>
			</form>
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
				<th>工号</th>
				<th>部门/班组</th>
				<th>姓名</th>
				<th>性别</th>
				<th>职务</th>
				<th>电话</th>
				<th>手机</th>
				<th>QQ</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="  pagination">
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AJAX_URL = "{{ url('api/weixiu/staff/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('weixiu/staff/add/0') }}"; //添加url
        const SHOW_URL = "{{url('accounts/info/')}}/";//显示页面地址前缀 + id
        const EDIT_URL = "{{url('weixiu/staff/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/weixiu/staff/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "{{ url('api/weixiu/staff/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('weixiu/staff/add/0') }}"; //"{{ url('api/weixiu/staff/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('weixiu/staff/add/0') }}"; //"{{ url('api/weixiu/staff/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/weixiu/lanmu/staff.js') }}"  type="text/javascript"></script>
@endpush