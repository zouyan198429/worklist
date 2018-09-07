<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录</title>
	<link rel="stylesheet" type="text/css" href="{{asset('weixiu/css/style.css')}}">
	<style type="text/css">
	#manlist { margin-top: 0px; text-align: center; }
	#manlist a { display: inline-block; margin-right: 20px;  width: 120px; height: 40px; line-height: 40px;}
	</style>
</head>
<body id="body-login">
	<div id="loginbox">
		<h1>欢迎登录<h1>
		<ul>
			<li><input type="text" value="用户名" /></li>
			<li><input type="text" value="密码" /></li>
			<li><input type="submit" onClick="window.open('index.html')" value="登录" class="btn" /></li>
		</ul>
	</div>	
	<div id="manlist">
	<a href="{{ url('huawu/index') }}" >话务员</a>
	<a href="{{ url('weixiu/index') }}" >维修工程师</a>
	<a href="{{ url('admin/index') }}" >管理层</a>
	</div>
</body>
</html>