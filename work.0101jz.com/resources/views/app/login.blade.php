<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>维修APP</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="{{asset('staticapp/css/style.css')}}">
 	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">	
</head>
<body>
	<div id="loginheader">
		<div id="logo">工单管理及业务培训系统</div>		 
	</div>

	<form action="#"  method="post"  id="addForm" >
	<section id="loginbox" >
			<h1>欢迎登录<h1>
			<ul>
				<li><input type="text"   name="admin_username"  placeholder="用户名"  /></li>
				<li><input type="password" name="admin_password"   placeholder="密码"/></li>
				<li><button type="submit"  id="submitBtn"  {{--onClick="window.open('index.html')"--}} >登录</button></li>
			</ul>
	</section>
	</form>
 
	 
</body>
</html>
<script src="{{asset('staticapp/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    const LOGIN_URL = "{{ url('api/app/ajax_login') }}";
    const INDEX_URL = "{{url('app/info')}}";

</script>
<script src="{{ asset('/js/common/login.js') }}"  type="text/javascript"></script>