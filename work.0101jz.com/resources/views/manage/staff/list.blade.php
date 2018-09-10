@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			@include('common.pageParams')

			<div class="tabbox" >
				<a href="javascript:void(0);" class="on " onclick="action.add()">添加员工</a>
			</div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<div class="msearch fr">

				<select class="wmini">
					<option value="a01">全部</option>
					<option value="a02">维修部</option>
					<option value="a03">话务部</option>
					<option value="a04">行政部</option>
				</select>
				<input type="text" value=""  name="keyword" />
				<button class="btn btn-normal search_frm " >搜索</button>
			</div>
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
				<th>
					<label class="pos-rel">
						<input type="checkbox" class="ace check_all"  value="" onclick="action.seledAll(this)"/>
						<span class="lbl">全选</span>
					</label>
				</th>
				<th>工号</th>
				<th>部门/班组</th>
				<th>姓名</th>
				<th>性别</th>
				<th>职务</th>
				<th>电话</th>
				<th>手机</th>
				<th>QQ</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			{{--
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			--}}
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pagination">
				{{--
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
				--}}
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AJAX_URL = "{{ url('api/manage/staff/ajax_alist') }}";//ajax请求的url
		const ADD_URL = "{{ url('manage/staff/add/0') }}"; //添加url
        const SHOW_URL = "{{url('accounts/info/')}}/";//显示页面地址前缀 + id
        const EDIT_URL = "{{url('manage/staff/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//删除页面地址
		const BATCH_DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('manage/staff/add/0') }}"; //"{{ url('api/manage/staff/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('manage/staff/add/0') }}"; //"{{ url('api/manage/staff/import') }}";//导入EXCEL地址

	</script>
    <script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/manage/lanmu/staff.js') }}"  type="text/javascript"></script>
@endpush