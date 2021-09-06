@extends('layouts.login')

@push('headscripts')
{{--  本页单独使用 --}}
<script src="{{asset('dist/lib/jquery-qrcode-master/jquery.qrcode.min.js')}}"></script>
@endpush

@section('bodyclass') class="bg-primary" @endsection
@section('content')
    <div class="page page-reg text-center">
        <div class="panel">
            <div class="panel-body">
                <div class="logo">
                    <a href="#" class="tishi_title">企业用户注册</a>
                </div>
                <div class="login-helpers text-left reg_tishi">
                    以下信息均为必填项,帐号可以填写手机号
                </div>
                <form action="#" method="post"  id="addForm">
                    <div class="form-group">
                        <input type="text" name="company_name" class="form-control" placeholder="企业名称">
                    </div>
                    <div class="form-group selModuleNos"  style=" text-align: left;">
                        @foreach ($module_no_kv as $k=>$txt)
                            <label>
                                <input type="checkbox" name="module_nos[]" value="{{ $k }}"  @if( isset($module_no) && ($module_no & $k) == $k) checked="checked"  @endif/>{{ $txt }}
                            </label>
                        @endforeach
                        <p>开通功能模块！注册后也可以联系我们变更！</p>
                    </div>
                    <div class="form-group">
                        <input type="text" name="company_linkman" class="form-control" placeholder="联系人">
                    </div>
                    <div class="form-group"  style="text-align: left;">
                        <label><input type="radio" name="sex" value="1">男</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        <label ><input type="radio" name="sex" value="2">女</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="company_mobile" class="form-control" placeholder="手机号"  onkeyup="isnum(this) " onafterpaste="isnum(this)" >
                    </div>
                    <div class="form-group"  style="text-align: left;">
                        <input type="text" name="admin_username" class="form-control" placeholder="用户名">
                        <p>超级管理员帐号，用于登录系统。</p>
                    </div>
                    <div class="form-group">
                        <input type="password" name="admin_password" class="form-control" placeholder="密码">
                    </div>
                    <div class="form-group">
                        <input type="password" name="sure_password"  class="form-control" placeholder="确认密码">
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <input type="text" name="real_name"  class="form-control" placeholder="真实姓名">--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="row">--}}
{{--                            @include('public.area_select.area_select', ['province_id' => 'province_id','city_id' => 'city_id','area_id' => 'area_id'])--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <input name="company_addr" type="text" class="form-control" placeholder="详细地址">--}}
{{--                    </div>--}}
                    <button type="button"   id="submitBtn" {{--onclick="window.open('{{ url('login') }}')" --}}   class="btn btn-lg btn-primary btn-block">注册</button>
                </form>
                <div class="login-helpers text-left reg_ok_block"  style="text-align: left; display: none;">
{{--                    <br> 已经有账户？ <a href="{{ url('login') }}" >马上登录</a>--}}
                    <p>请保存好您的《管理后台》、《员工H5系统》 访问地址。</p>
                    <p class="web_block">
                        管理后台：
                        <span class="web_url"></span>
                        <input type="button" class="btn btn-success  btn-xs export_excel"  value="复制"  onclick="otheraction.copyWebUrl(this)">
                        <br/><span class="qrcode" data-qrcodeurl=""></span>
                        <br/><a class="web_url_a" href="#" target="_blank" >马上登录，使用系统</a>
                    </p>
                    <p class="m_block">
                        员工H5系统： <span class="h5_url"></span>
                        <input type="button" class="btn btn-success  btn-xs export_excel"  value="复制"  onclick="otheraction.copyH5Url(this)">
                        <br/><span class="qrcode" data-qrcodeurl=""></span>
{{--                    <br/><a class="h5_url_a" href="#" target="_blank">马上登录，使用系统</a>--}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footscripts')
    <!-- BaiduTemplate -->
    {{--111 @include('public.table_page_baidu_template') --}}
<!-- BaiduTemplate-->
<script src="{{ asset('/static/js/custom/baiduTemplate.js') }}"></script>
<script>
    var REG_URL = '{{ url('api/ajax_reg') }}';
    var LOGIN_URL = "{{url('login')}}";

</script>
<script src="{{ asset('/js/lanmu/reg.js') }}?3"  type="text/javascript"></script>
@endpush
