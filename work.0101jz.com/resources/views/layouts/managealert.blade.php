@include('manage.layout_public.pagehead')
<body>
{{--@include('manage.layout_public.header')
@include('manage.layout_public.sidebar')--}}
{{--<div id="main">--}}
    {{-- 主操作区域内容 --}}
    @yield('content')
{{--</div>--}}


</body>
</html>

{{-- 本页单独foot使用,可以</html>结尾后可以写的内容，如js引入或操作 --}}
@stack('footscripts')
@include('manage.layout_public.pagefoot')
@stack('footlast')
