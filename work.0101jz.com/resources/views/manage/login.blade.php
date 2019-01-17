<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录</title>
	<link rel="stylesheet" type="text/css" href="{{asset('staticmanage/css/style.css')}}">
	<style type="text/css">
	#manlist { margin-top: 0px; text-align: center; }
	#manlist a { display: inline-block; margin-right: 20px;  width: 120px; height: 40px; line-height: 40px;}
	</style>
	@include('manage.layout_public.piwik')
</head>
<body id="body-login">
	<form class="am-form" action="#"  method="post"  id="addForm">
		<div id="loginbox">
			<h1>欢迎登录<h1>
			<ul>
				<li><input type="text"  name="admin_username"  placeholder="用户名" /></li>
				<li><input type="password"  name="admin_password"  placeholder="密码" /></li>
				<li><input type="submit"  id="submitBtn"  {{--onClick="window.open('index.html')"--}} value="登录" class="btn" /></li>
			</ul>
		</div>
	</form>
	<div id="manlist">
	<a href="http://kefu.0101jz.com" >话务员</a>
	<a href="http://shouhou.0101jz.com" >维修工程师</a>
	<a href="http://admin.0101jz.com" >管理层</a>
	</div>
</body>
</html>
<script src="{{asset('staticmanage/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    const LOGIN_URL = "{{ url('api/manage/ajax_login') }}";
    const INDEX_URL = "{{url('manage')}}";

</script>
<script src="{{ asset('/js/common/login.js') }}"  type="text/javascript"></script>