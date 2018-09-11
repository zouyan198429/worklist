@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加分数等级</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>名称<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" name="grade_name" value="{{ $grade_name or '' }}" placeholder="等级名称"  autofocus  required />
				</td>
			</tr>
			<tr>
				<th>结束分数(<=)</th>
				<td>
					<input type="number" class="inp wnormal"  name="max_score" onkeyup="isnum(this) " onafterpaste="isnum(this)"  value="{{ $max_score or '' }}"   placeholder="请输入整数" autofocus  required />
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
        const SAVE_URL = "{{ url('api/admin/core_grade/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('admin/core_grade')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/admin/lanmu/core_grade_edit.js') }}"  type="text/javascript"></script>
@endpush