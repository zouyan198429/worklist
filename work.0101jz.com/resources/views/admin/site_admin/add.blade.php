@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加管理员</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>职位/角色<span class="must">*</span></th>
				<td>
					<select class="wnormal" name="admin_type">
						<option value="">请选择职位/角色</option>
						@foreach ($admin_types as $k=>$type)
							<option value="{{ $k }}"  @if(isset($admin_type) && $admin_type == $k) selected @endif >{{ $type }}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<th>用户名<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal"  name="admin_username" value="{{ $admin_username or '' }}" placeholder="用户名"  autofocus  required />
				</td>
			</tr>
			<tr>
				<th>登录密码<span class="must">*</span></th>
				<td>
					<input type="password"  class="inp wnormal"   name="admin_password" placeholder="登录密码" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>确认密码<span class="must">*</span></th>
				<td>
					<input type="password" class="inp wnormal"     name="sure_password"  placeholder="确认密码" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>真实姓名<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal"    name="real_name" value="{{ $real_name or '' }}" placeholder="输入真实姓名" autofocus  required />
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
        const SAVE_URL = "{{ url('api/admin/site_admin/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('admin/site_admin')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/admin/lanmu/site_admin_edit.js') }}"  type="text/javascript"></script>
@endpush