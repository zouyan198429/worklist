
<div id="sidbar" >
    <section  class="sidebar">
        <ul class="sidebar-menu" id="subnav">
            <li>
                <a href="{{ url('weixiu/index') }}"><i class="fa fa-home fa-fw" aria-hidden="true"></i> 首页</a>
            </li>
            <li>
                <a href="{{ url('weixiu/problem/add') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>反馈问题</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/work/list') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>维修清单</span>
                </a>
            </li>

            <li>
                <a href="{{ url('weixiu/customer/index') }}">
                    <i class="fa fa-star-o fa-fw" aria-hidden="true"></i>
                    <span>我的客户</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/customer/dayCount') }}">
                    <i class="fa fa-bar-chart fa-fw" aria-hidden="true"></i>
                    <span>我的业绩</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/staff/index') }}">
                    <i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>
                    <span>我的同事</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/lore/index') }}">
                    <i class="fa fa-battery-3 fa-fw" aria-hidden="true"></i>
                    <span>在线学习</span>
                </a>
            </li>
            <li>
                <a href="{{ url('weixiu/exam/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>在线考试</span>
                </a>
            </li>
        </ul>
    </section>
    </aside>
</div>