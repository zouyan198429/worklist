<div id="header">
    <div id="logo">管理后台</div>
    <div id="userinfo">
        <div class="dropdown">
            <span><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> 欢迎您：{{ $baseArr['real_name'] or '' }} <i class="fa fa-angle-down fa-fw" aria-hidden="true"></i>  </span>
            <ul class="dropdown-content">
                <li><a href="{{ url('admin/info') }}" >我的帐号</a></li>
                <li><a href="{{ url('admin/password') }}" >修改密码</a></li>
            </ul>
        </div>
        <span class="quit"><a href="{{ url('admin/' . $baseArr ['company_id'] . '/logout') }}"><i class="fa fa-power-off fa-fw" aria-hidden="true"></i>退出</a></span></div>
</div>
