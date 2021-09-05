@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加部门</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>所属区县<span class="must">*</span></th>
				<td>
					<select class="wnormal" name="department_parent_id">
						<option value="">请选择区县</option>
						<option value="0"  @if ( 0 == $department_parent_id ) selected @endif>父级部门</option>
						@foreach ($department_kv as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($department_parent_id) && $department_parent_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<th>营业厅或片区<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" name="department_name" value="{{ $department_name or '' }}" placeholder="部门/小组名称" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>排序[降序]</th>
				<td>
					<input type="number" class="inp wnormal"  name="sort_num" onkeyup="isnum(this) " onafterpaste="isnum(this)"  value="{{ $sort_num or '' }}"   placeholder="请输入整数" autofocus  required />
				</td>
			</tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"  id="submitBtn" >提交</button></td>
			</tr>

		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/admin/department/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('admin/department')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/admin/lanmu/department_edit.js') }}"  type="text/javascript"></script>
@endpush