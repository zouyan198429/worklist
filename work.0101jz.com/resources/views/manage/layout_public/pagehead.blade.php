<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理后台</title>
    @include('public.dynamic_list_head')
    <link rel="stylesheet" href="{{asset('staticmanage/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('staticmanage/css/sidebar-menu.css')}}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <style type="text/css">
        .main-sidebar{
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            min-height: 100%;
            width: 200px;
            z-index: 810;
            background-color: #222d32;
        }
    </style>
    <script src="{{asset('staticmanage/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
    {{-- 本页单独head使用 --}}
    @stack('headscripts')
    @include('manage.layout_public.piwik')
</head>