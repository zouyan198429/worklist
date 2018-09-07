<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>员工登录</title>
	<link rel="stylesheet" type="text/css" href="{{asset('admin/css/style.css')}}">
</head>
<body id="body-login">
	<div id="loginbox">
		<h1>欢迎登录<h1>
		<ul>
			<li><input type="text" value="用户名" /></li>
			<li><input type="text" value="密码" /></li>
			<li><input type="submit" onClick="window.open('{{ url('admin/index') }}')" value="登录" class="btn" /></li>
		</ul>
	</div>
	
</body>
</html>