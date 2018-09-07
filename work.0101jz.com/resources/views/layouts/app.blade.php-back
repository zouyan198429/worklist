@include('pagehead')
<body>
<div class="wrapper">
    <header class="main-header">
        @include('header')
    </header>
    <aside class="main-sidebar">
        @include('sidebar')
    </aside>
    <div class="content-wrapper">
        {{-- 主操作区域内容 --}}
        @yield('content')
    </div>
</div>
</body>
</html>
{{-- 本页单独foot使用,可以</html>结尾后可以写的内容，如js引入或操作 --}}
@stack('footscripts')
@include('pagefoot')
@stack('footlast')