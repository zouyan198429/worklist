<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录---工单管理及业务培训系统</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="{{asset('staticmobile/css/style.css')}}">
 	{{--<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">--}}
	<link rel="stylesheet" href="{{asset('font-awesome-4.7.0/css/font-awesome.css')}}">
	@include('mobile.layout_public.piwik')
</head>
<body>
<div class="page" id="login">
	<div id="loginheader">
		<img src="http://pic.0101jz.com/logo-ydapp3.png" alt="移动工单管理系统">
 	</div>

	<form action="#"  method="post"  id="addForm" >
	<section id="loginbox" > 
			<h1>员工登录<h1>
			<ul>
				<li><input type="text"  name="admin_username"  placeholder="工号" /></li>
				<li><input type="password" name="admin_password"   placeholder="密码" /></li>
				<li><button type="submit" id="submitBtn"  {{-- onClick="window.open('{{ url('m') }}')" --}} >登录</button></li>
			</ul>
	</section>
	</form> 


 
</div>
</body>
</html>
<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- 新加入 end-->
<script>
    var LOGIN_URL = "{{ url('api/m/ajax_login') }}";
	@if(isset($webType) && $webType == 2)
    	var INDEX_URL = "{{url('m')}}";
    @else
    	var INDEX_URL = "{{url('m/problem')}}";
	@endif
</script>
<script src="{{ asset('/js/common/loginuser.js') }}"  type="text/javascript"></script>