@include('huawu.layout_public.pagehead')
<body>
@include('huawu.layout_public.header')

@include('huawu.layout_public.sidebar')
<div id="main">
    {{-- 主操作区域内容 --}}
    @yield('content')
</div>

</body>
</html>
{{-- 本页单独foot使用,可以</html>结尾后可以写的内容，如js引入或操作 --}}
@stack('footscripts')
@include('huawu.layout_public.pagefoot')
@stack('footlast')