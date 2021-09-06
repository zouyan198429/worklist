@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 个人信息</div>
	<div class="mm">
		<form  method="post"  id="addForm">
		<table class="table1">

			<tr>
				<th>工号</th>
				<td>
					{{ $work_num or '' }}
				</td>
			</tr>
			<tr>
				<th>姓名</th>
				<td>
					{{ $real_name or '' }}
				</td>
			</tr>
			<tr>
				<th>旧密码</th>
				<td>
					<input  name="old_password"  type="password"  placeholder="请输入旧密码" class="inp wlong" />
				</td>
			</tr>
			<tr>
				<th>新密码</th>
				<td>
					<input  name="admin_password"  type="password"  placeholder="请输入密码" class="inp wlong" /> <span>新密码须6位以上，含英文及数字，建议首字母大写。</span>
				</td>
			</tr>
			<tr>
				<th>确认新密码</th>
				<td>
					<input  name="sure_password"  type="password"  placeholder="请再次输入密码"  class="inp wlong" />
				</td>
			</tr>

			<tr>
				<th>
				</th>
				<td><button type="button" id="submitBtn"  class="btn btn-l wnormal" >提交</button></td>
			</tr>
		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script>
        const SAVE_URL = "{{ url('api/weixiu/ajax_password_save') }}";
        const SET_URL = "{{url('weixiu/' . $baseArr ['company_id'] . '/logout')}}";//"{{url('weixiu/password')}}"
	</script>
	<script src="{{ asset('js/common/staff_password.js') }}"  type="text/javascript"></script>
@endpush
