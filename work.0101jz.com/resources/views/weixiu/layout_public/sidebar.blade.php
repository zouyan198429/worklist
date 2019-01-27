
<div id="sidbar" >
    <section  class="sidebar">
        <ul class="sidebar-menu" id="subnav">
            <li>
                <a href="{{ url('weixiu') }}"><i class="fa fa-home fa-fw" aria-hidden="true"></i> 首页</a>
            </li>
            @if(isset($webType) && $webType == 2)
            <li>
                <a href="{{ url('weixiu/work/list') }}">
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>我的工单</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ url('weixiu/problem/add/0') }}">
                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i>
                    <span>反馈问题</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/problem') }}">
                    <i class="fa fa-edit fa-fw" aria-hidden="true"></i>
                    <span>反馈问题列表</span>
                </a>
            </li>
            @if(isset($webType) && $webType == 2)
            <li>
                <a href="{{ url('weixiu/customer') }}">
                    <i class="fa fa-star-o fa-fw" aria-hidden="true"></i>
                    <span>我的客户</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/customer/day_count') }}">
                    <i class="fa fa-bar-chart fa-fw" aria-hidden="true"></i>
                    <span>我的业绩</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/notice') }}">
                    <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>
                    <span>通知公告</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ url('weixiu/staff') }}">
                    <i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>
                    <span>我的同事</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/lore') }}">
                    <i class="fa fa-battery-3 fa-fw" aria-hidden="true"></i>
                    <span>在线学习</span>
                </a>
            </li>
            @if(isset($webType) && $webType == 2)
            <li>
                <a href="{{ url('weixiu/exam') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>在线考试</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    </aside>
</div>