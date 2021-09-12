
<div id="header">
    <div id="logo">工单管理及业务培训系统</div>
    <div id="userinfo">
        <div class="dropdown">
            <span><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> 欢迎您：{{ $baseArr['real_name'] or '' }} <i class="fa fa-angle-down fa-fw" aria-hidden="true"></i>  </span>
            <ul class="dropdown-content">
                <li><a href="{{ url('huawu/info') }}" >我的帐号</a></li>
                @if(isset($baseArr['account_type']) && $baseArr['account_type'] != 2)
                <li><a href="{{ url('huawu/password') }}" >修改密码</a></li>
                @endif
            </ul>
        </div>
        <span class="quit"><a href="{{ url('huawu/' . $baseArr ['company_id'] . '/logout') }}"><i class="fa fa-power-off fa-fw" aria-hidden="true"></i>退出</a></span></div>
</div>
