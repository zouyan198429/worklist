
<div id="sidbar" >
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('huawu') }}"><i class="fa fa-home fa-fw" aria-hidden="true"></i> 首页</a>
            </li>
            <li>
                <a href="{{ url('huawu/work/add') }}">
                    <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i>
                    <span>来电记录</span>
                    <small class="label pull-right label-info">HOT</small>
                </a>
            </li>
            <li class="treeview">
                <a href="" >
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>我的工作</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="{{ url('huawu/work/history') }}"><i class="fa fa-circle-o"></i> 历史工单</a></li>
                    <li><a href="{{ url('huawu/work/hot') }}"><i class="fa fa-circle-o"></i> 重点关注</a></li>
                    <li><a href="{{ url('huawu/work/re_list') }}"><i class="fa fa-circle-o"></i> 客户回访</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ url('huawu/customer') }}">
                    <i class="fa fa-star-o fa-fw" aria-hidden="true"></i>
                    <span>我的客户</span>
                </a>
            </li>
            <li>
                <a href="{{ url('huawu/customer/dayCount') }}">
                    <i class="fa fa-bar-chart fa-fw" aria-hidden="true"></i>
                    <span>我的业绩</span>
                </a>
            </li>
            <li>
                <a href="{{ url('huawu/staff') }}">
                    <i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>
                    <span>我的同事</span>
                </a>
            </li>
            <li>
                <a href="{{ url('huawu/lore') }}">
                    <i class="fa fa-battery-3 fa-fw" aria-hidden="true"></i>
                    <span>在线学习</span>
                </a>
            </li>
            <li>
                <a href="{{ url('huawu/exam') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>在线考试</span>
                </a>
            </li>
        </ul>
    </section>
    </aside>
</div>