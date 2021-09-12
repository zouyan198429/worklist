<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>错误</title>
	<link rel="stylesheet" type="text/css" href="{{asset('staticadmin/css/style.css')}}">
	@include('admin.layout_public.piwik')
</head>
<body id="body-login">
		<div id="loginbox">
			<h1>错误</h1>
            <p>{{ $errStr or '' }}</p>
		</div>
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