@extends('layouts.m')

@push('headscripts')
    {{--  本页单独使用 --}}
@endpush

@section('content')

    <div class="page">
        <div id="header">
            <div class="top-title">问题反馈</div>
        </div>
        <div id="prlblem" class="table4" >
            <dl class="inp">
                <dt>问题类型</dt>
                <dd>
                    <select class="wnormal">
                        <option value="a01">固定电话</option>
                        <option value="a02">宽带业务</option>
                        <option value="a03">手机业务</option>
                        <option value="a04">其他</option>
                    </select>
                    <select class="wnormal">
                        <option value="a01">新装</option>
                        <option value="a02">断网</option>
                        <option value="a03">迁移</option>
                        <option value="a04">其他</option>
                    </select>
                </dd>
            </dl>
            <dl class="inp">
                <!-- 					<dt>反馈内容<span class="must">*</span></dt>
                 -->					<dd>
                    <textarea type="text" class="inptext wlong"  style=" height:200px"  placeholder="反馈内容" /></textarea>
                </dd>
            </dl>
            <div class="k10"></div>
            <div class="line"></div>
            <div class="k10"></div>
            <dl class="inp">
                <!-- 					<dt>客户电话</dt>
                 -->					<dd>
                    <input type="text" class="inp wlong" value="" placeholder="客户电话" autofocus  required />
                </dd>
            </dl>
            <dl class="inp">
                <dt>客户地址</dt>
                <dd>
                    <select class="wnormal">
                        <option value="a01">区</option>
                        <option value="a02">宽带业务</option>
                        <option value="a03">手机业务</option>
                        <option value="a04">其他</option>
                    </select>
                    <select class="wnormal">
                        <option value="a01">街道</option>
                        <option value="a02">宽带业务</option>
                        <option value="a03">手机业务</option>
                        <option value="a04">其他</option>
                    </select>
                    <div class="k10"></div>
                    <input type="text" class="inp wlong" placeholder="详细地址"  />
                </dd>
            </dl>
            <dl>
                <dt> </dt>
                <div class="k10"></div>
                <dd><button class="btn btn-l wlong" >提交</button>
                </dd>
            </dl>

        </div>

        @include('mobile.layout_public.menu', ['menu_id' => 5])


    </div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    {{--<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('staticmobile/js/sidebar-menu.js')}}"></script>
    <script>
        $.sidebarMenu($('.sidebar-menu'))
    </script>

@endpush
