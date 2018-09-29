@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈分类</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加分类</a>
			</div>
		</div>
		<table  id="dynamic-table" class="table2">
			<thead>
			<tr>
				<th>分类</th>
				<th>业务</th>
				<th>排序</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			{{--
			<tr>
				<td>固话业务</td>
				<td>新装</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			<tr>
				<td> </td>
				<td>移机</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>


			<tr>
				<td>手机业务</td>
				<td>新装</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			<tr>
				<td>宽带业务</td>
				<td>新装</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			--}}
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
        const AJAX_URL = "{{ url('api/admin/problem_type/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('admin/problem_type/add/0') }}"; //添加url
        const SHOW_URL = "";// {{url('problem_type/info/')}}/";//显示页面地址前缀 + id
        const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        const EDIT_URL = "{{url('admin/problem_type/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/admin/problem_type/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "";// {{ url('api/manage/problem_type/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = ""; // {{ url('manage/problem_type/add/0') }}"; //"{{ url('api/manage/problem_type/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL =""; // "{{ url('manage/problem_type/add/0') }}"; //"{{ url('api/manage/problem_type/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('/js/admin/lanmu/problem_type.js') }}"  type="text/javascript"></script>
@endpush