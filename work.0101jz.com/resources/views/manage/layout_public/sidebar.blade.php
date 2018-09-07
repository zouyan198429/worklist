
<div id="sidbar" >
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('manage/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>主管首页</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/work/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>工单管理</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/staff/index') }}">
                    <i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>
                    <span>我的同事</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/lore/list') }}">
                    <i class="fa fa-battery-3 fa-fw" aria-hidden="true"></i>
                    <span>在线学习</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/staff/list') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>员工管理</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/problem/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>反馈问题</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/customer/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>客户管理</span>
                </a>
            </li>
            <li>
                <a href="{{ url('manage/lore/index') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>知识管理</span>
                </a>
            </li>
            <li class="treeview">
                <a href="" >
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>考试管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="{{ url('manage/subject/index') }}"><i class="fa fa-circle-o"></i> 试题管理</a></li>
                    <li><a href="{{ url('manage/paper/index') }}"><i class="fa fa-circle-o"></i> 试卷生成</a></li>
                    <li><a href="{{ url('manage/exam/index') }}"><i class="fa fa-circle-o"></i> 考试管理</a></li>
                    <li><a href="x_achievement.html"><i class="fa fa-circle-o"></i> 成绩查看</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="" >
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>数据统计</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="{{ url('manage/count_call/index') }}"><i class="fa fa-circle-o"></i> 来电统计</a></li>
                    <li><a href="{{ url('manage/count_repair/index') }}"><i class="fa fa-circle-o"></i> 维修数据</a></li>
                    <li><a href="{{ url('manage/count_customer/index') }}"><i class="fa fa-circle-o"></i> 客户数据</a></li>
                </ul>
            </li>
        </ul>
    </section>
    </aside>
</div>