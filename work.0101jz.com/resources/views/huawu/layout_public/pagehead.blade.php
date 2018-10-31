<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>总部客服平台</title>
    {{-- 本页单独head使用 --}}
    @stack('preheadscripts')
    @include('public.dynamic_list_head')
    <link rel="stylesheet" href="{{asset('statichuawu/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('statichuawu/css/sidebar-menu.css')}}">
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">--}}
    <link rel="stylesheet" href="{{asset('font-awesome-4.7.0/css/font-awesome.css')}}">
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
    <script src="{{asset('statichuawu/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
    {{-- 本页单独head使用 --}}
    @stack('headscripts')
    @include('huawu.layout_public.piwik')
</head>