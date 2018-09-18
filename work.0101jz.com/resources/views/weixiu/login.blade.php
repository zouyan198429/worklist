<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录--区县人员</title>
	<link rel="stylesheet" type="text/css" href="{{asset('staticweixiu/css/style.css')}}">
	<style type="text/css">
	#manlist { margin-top: 0px; text-align: center; }
	#manlist a { display: inline-block; margin-right: 20px;  width: 120px; height: 40px; line-height: 40px;}
	</style>
</head>
<body id="body-login">
	<form action="#"  method="post"  id="addForm" >
		<div id="loginbox">
			<h1>欢迎登录<h1>
			<ul>
				<li><input type="text"  name="admin_username"  placeholder="用户名" /></li>
				<li><input type="password"  name="admin_password"   placeholder="密码"  /></li>
				<li><input type="submit"  id="submitBtn"  {{-- onClick="window.open('index.html')"--}} value="登录" class="btn" /></li>
			</ul>
		</div>
	</form>
<!-- 	<div id="manlist">
	<a href="{{ url('huawu') }}" >话务员</a>
	<a href="{{ url('weixiu') }}" >维修工程师</a>
	<a href="{{ url('admin') }}" >管理层</a>
	</div> -->
</body>
</html>
<script src="{{asset('staticweixiu/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    const LOGIN_URL = "{{ url('api/weixiu/ajax_login') }}";
    const INDEX_URL = "{{url('weixiu')}}";

</script>
<script src="{{ asset('/js/common/login.js') }}"  type="text/javascript"></script>