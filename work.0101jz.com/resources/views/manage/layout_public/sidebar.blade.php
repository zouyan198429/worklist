
<div id="sidbar" >
    <section  class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('manage') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>管理首页</span>
                </a>
            </li>
            @if( (isset($webType) && $webType == 2) && (isset($baseArr['module_no']) && ($baseArr['module_no'] & 8) == 8))
            <li>
                <a href="{{ url('manage/work') }}">
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>工单信息</span>
                </a>
            </li>
            @endif
            @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 4) == 4)
            <li>
                <a href="{{ url('manage/problem') }}">
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    <span>反馈问题</span>
                </a>
            </li>
            @endif
            @if(isset($webType) && $webType == 2)
            <li>
                <a href="{{ url('manage/notice') }}">
                    <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>
                    <span>通知公告</span>
                </a>
            </li>
            @if( isset($baseArr['module_no']) && ($baseArr['module_no'] & 16) == 16)
            <li>
                <a href="{{ url('manage/staff') }}">
                    <i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>
                    <span>我的同事</span>
                </a>
            </li>
            @endif
            @endif
            @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 1) == 1)
            <li>
                <a href="{{ url('manage/lore/list') }}">
                    <i class="fa fa-battery-3 fa-fw" aria-hidden="true"></i>
                    <span>在线学习</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ url('manage/staff/list') }}">
                    <i class="fa fa-user-o fa-fw" aria-hidden="true"></i>
                    <span>员工管理</span>
                </a>
            </li>
            @if(isset($webType) && $webType == 2)
            @if( isset($baseArr['module_no']) && ($baseArr['module_no'] & 8) == 8)
            <li>
                <a href="{{ url('manage/customer') }}">
                    <i class="fa fa-address-card-o fa-fw" aria-hidden="true"></i>
                    <span>客户管理</span>
                </a>
            </li>
            @endif
            @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 1) == 1)
            <li>
                <a href="{{ url('manage/lore') }}">
                    <i class="fa fa-book fa-fw" aria-hidden="true"></i>
                    <span>知识管理</span>
                </a>
            </li>
            @endif
            @if( isset($baseArr['module_no']) && ($baseArr['module_no'] & 2) == 2)
            <li class="treeview">
                <a href="" >
                    <i class="fa fa-check-square fa-fw" aria-hidden="true"></i>
                    <span>考试管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="{{ url('manage/subject') }}"><i class="fa fa-circle-o"></i> 第一步：试题管理</a></li>
                    <li><a href="{{ url('manage/paper') }}"><i class="fa fa-circle-o"></i> 第二步：试卷生成</a></li>
                    <li><a href="{{ url('manage/exam') }}"><i class="fa fa-circle-o"></i> 第三步：考试管理</a></li>
                    {{--<li><a href="x_achievement.html"><i class="fa fa-circle-o"></i> 成绩查看</a></li>--}}
                </ul>
            </li>
            @endif
            @if( isset($baseArr['module_no']) && ($baseArr['module_no'] & 8) == 8)
            <li class="treeview">
                <a href="" >
                    <i class="fa fa-bar-chart fa-fw" aria-hidden="true"></i>
                    <span>数据统计</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="{{ url('manage/count_call') }}"><i class="fa fa-circle-o"></i> 来电统计</a></li>
                    <li><a href="{{ url('manage/count_repair') }}"><i class="fa fa-circle-o"></i> 工单处理</a></li>
                    {{--<li><a href="{{ url('manage/count_customer') }}"><i class="fa fa-circle-o"></i> 客户数据</a></li>--}}
                </ul>
            </li>
            @endif
            @endif
        </ul>
    </section>
{{--    </aside>--}}
</div>
