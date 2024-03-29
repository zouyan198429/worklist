<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>系统登录</title>
	<link rel="stylesheet" type="text/css" href="{{asset('staticadmin/css/style.css')}}">
	@include('admin.layout_public.piwik')
</head>
<body id="body-login">
	<form class="am-form" action="#"  method="post"  id="addForm">
		<div id="loginbox">
			<h1>欢迎登录</h1>
            <h3>{{ $company_info['company_name'] or '' }}</h3>
            <input type="hidden"  name="company_id"   value="{{ $company_info['id'] or '' }}" />
			<ul>
				<li><input type="text"  name="admin_username"   placeholder="工号" /></li>
				<li><input type="password"  name="admin_password" style="font-size:14px;" placeholder="密码" /></li>
				<li style="" >
					<select  name="system_id" style="font-size:14px; width: 100%; margin-bottom:25px; height:38px;"  >
						<option value="">请选择登录平台</option>
						@foreach ($system_kv as $k=>$txt)
                            @if($k != 3 || ($k == 3 && isset($baseArr['module_no']) && ($baseArr['module_no'] & 8) != 8))
							<option value="{{ $k }}"  @if(isset($defaultSystem) && $defaultSystem == $k) selected @endif >{{ $txt }}</option>
                            @endif
						@endforeach
					</select>
				</li>
				<li><input type="submit" id="submitBtn"  {{--onClick="window.open('{{ url('admin') }}')"--}} value="登录" class="btn" /></li>
			</ul>
		</div>
	</form>

</body>
</html>
{{--<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>--}}
<script src="{{asset('js/admin/jquery2.1.4/jquery.min.js')}}"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    var LOGIN_ADMIN_URL = "{{ url('api/admin/ajax_login') }}";
    var LOGIN_MANAGE_URL = "{{ url('api/manage/ajax_login') }}";
    var LOGIN_HUAWU_URL = "{{ url('api/huawu/ajax_login') }}";
    var LOGIN_WEIXIU_URL = "{{ url('api/weixiu/ajax_login') }}";

    var INDEX_ADMIN_URL = "{{url('admin')}}";
    var INDEX_MANAGE_URL = "{{url('manage')}}";
    var INDEX_HUAWU_URL = "{{url('huawu')}}";
    var INDEX_WEIXIU_URL = "{{url('weixiu')}}";

</script>
<script src="{{ asset('/js/common/loginNew.js') }}?1"  type="text/javascript"></script>
