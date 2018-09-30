<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>超级管理员登录</title>
	<link rel="stylesheet" type="text/css" href="{{asset('staticadmin/css/style.css')}}">
	@include('admin.layout_public.piwik')
</head>
<body id="body-login">
	<form class="am-form" action="#"  method="post"  id="addForm">
		<div id="loginbox">
			<h1>欢迎登录<h1>
			<ul>
				<li><input type="text"  name="admin_username"   placeholder="用户名" /></li>
				<li><input type="password"  name="admin_password"  placeholder="密码" /></li>
				<li><input type="submit" id="submitBtn"  {{--onClick="window.open('{{ url('admin') }}')"--}} value="登录" class="btn" /></li>
			</ul>
		</div>
	</form>
	
</body>
</html>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    const LOGIN_URL = "{{ url('api/admin/ajax_login') }}";
    const INDEX_URL = "{{url('admin')}}";

</script>
<script src="{{ asset('/js/common/login.js') }}"  type="text/javascript"></script>