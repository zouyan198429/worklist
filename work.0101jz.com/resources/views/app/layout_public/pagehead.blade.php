<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>维修APP</title>
    @include('public.dynamic_list_head')
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('app/css/style.css')}}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <script src="{{asset('app/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app/js/app.js')}}"></script>
    {{-- 本页单独head使用 --}}
    @stack('headscripts')
    @include('app.layout_public.piwik')

</head>