@include('app.layout_public.pagehead')
<body>
@include('app.layout_public.header')
@include('app.layout_public.sidebar')
{{-- 主操作区域内容 --}}
@yield('content')
<div id="mya"></div>
{{--<div id="demo"></div>--}}
</body>
</html>
{{-- 本页单独foot使用,可以</html>结尾后可以写的内容，如js引入或操作 --}}
@stack('footscripts')
@include('app.layout_public.pagefoot')
@stack('footlast')