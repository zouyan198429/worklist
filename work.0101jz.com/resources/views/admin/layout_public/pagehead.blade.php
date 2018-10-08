<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理后台</title>
    @include('public.dynamic_list_head')
    <link rel="stylesheet" type="text/css" href="{{asset('staticadmin/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('staticadmin/css/mune.css')}}">
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">--}}
    <link rel="stylesheet" href="{{asset('font-awesome-4.7.0/css/font-awesome.css')}}">
    {{--<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>--}}
    <script src="{{asset('js/admin/jquery2.1.4/jquery.min.js')}}"></script>
    {{-- 本页单独head使用 --}}
    @stack('headscripts')
    @include('admin.layout_public.piwik')
</head>