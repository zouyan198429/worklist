
<div id="sidbar" >
    <div class="mmhead" id="achtable">
        <ul id="accordion" class="accordion">
            <li>
                <div class="link"><a href="{{ url('admin') }}" ><i class="fa fa-paint-brush"></i>首页</a></div>
            </li>
            <li>
                <div class="link"><a href="{{ url('admin/work') }}" ><i class="fa fa-paint-brush"></i>工单列表</a></div>
            </li>
            <li>
                <div class="link"><a href="{{ url('admin/problem') }}" ><i class="fa fa-paint-brush"></i>回馈问题</a></div>
            </li>
            <li>
                <div class="link"><i class="fa fa-globe"></i><a href="{{ url('admin/customer') }}">客户管理</a></i></div>
            </li>
            <li>
                <div class="link"><i class="fa fa-globe"></i><a href="{{ url('admin/staff') }}">员工信息</a></div>
            </li>
            <li><div class="link"><i class="fa fa-globe"></i>知识管理<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/lore') }}">知识库</a></li>
                    <li><a href="{{ url('admin/lore_type') }}">知识分类</a></li>
                </ul>
            </li>
            <li><div class="link"><i class="fa fa-globe"></i>在线考试<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/exam') }}">考次管理</a></li>
                    <li><a href="{{ url('admin/subject') }}">试题管理</a></li>
                    <li><a href="{{ url('admin/paper') }}">试卷生成</a></li>
                    <li><a href="{{ url('admin/subject_type') }}">试题分类</a></li>
                </ul>
            </li>
            <li><div class="link"><i class="fa fa-globe"></i>数据统计<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/count_call') }}"> 来电统计</a></li>
                    <li><a href="{{ url('admin/count_repair') }}"> 维修数据</a></li>
                    <li><a href="{{ url('admin/count_customer') }}"> 客户数据</a></li>
                </ul>
            </li>
            <li><div class="link"><i class="fa fa-globe"></i>系统设置<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/department') }}">部门管理</a></li>
                    <li><a href="{{ url('admin/customer_type') }}">客户分类</a></li>
                    <li><a href="{{ url('admin/workCallerType') }}">来电分类</a></li>
                    <li><a href="{{ url('admin/work_type') }}">工单分类</a></li>
                    <li><a href="{{ url('admin/tags') }}">业务标签</a></li>
                    <li><a href="{{ url('admin/area') }}">区域设置</a></li>
                    <li><a href="{{ url('admin/roles') }}">角色权限</a></li>
                    <li><a href="{{ url('admin/siteAdmin') }}">管理员</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>