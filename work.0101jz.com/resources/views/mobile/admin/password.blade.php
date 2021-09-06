@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<form  method="post"  id="addForm">
	<div class="page">
		<div id="header">
			<div class="top-title">问题反馈</div>
		</div>
		<div id="prlblem" class="table4" >
			<dl class="inp">
				<dt>旧密码</dt>
				<dd>
					<input  name="old_password"  type="password"  placeholder="请输入旧密码" class="inp wlong" />
				</dd>
			</dl>
			<dl class="inp">
				<dt>新密码</dt>
				<dd>
					<input  name="admin_password"  type="password"  placeholder="请输入密码" class="inp wlong" />
				</dd>
			</dl>
			<dl class="inp">
				<dt>确认新密码</dt>
				<dd>
					<input  name="sure_password"  type="password"  placeholder="请再次输入密码"  class="inp wlong" />
				</dd>
			</dl>
			<dl>
				<dt> </dt>
				<div class="k10"></div>
				<dd><button  id="submitBtn"  class="btn btn-l wlong" >提交</button>
				</dd>
			</dl>



		</div>

		@include('mobile.layout_public.menu', ['menu_id' => 4])


	</div>
	</form>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	{{--<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>--}}
	<script src="{{asset('staticmobile/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>

	<script>
        const SAVE_URL = "{{ url('api/m/ajax_password_save') }}";
        const SET_URL = "{{url('m/' . $baseArr ['company_id'] . '/logout')}}";//"{{url('m/password')}}"
	</script>
	<script src="{{ asset('js/common/staff_password.js') }}"  type="text/javascript"></script>
@endpush
