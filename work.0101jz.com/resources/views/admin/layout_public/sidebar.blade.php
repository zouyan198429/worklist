
<div id="sidbar" >
    <div class="mmhead" id="achtable">
        <ul id="accordion" class="accordion">
            <li>
                <div class="link"><a href="{{ url('admin') }}"><i class="fa fa-home"></i>首页</a></div>
            </li>
            @if(isset($webType) && $webType == 2)
            <li>
                <div class="link"><a href="{{ url('admin/work') }}"  ><i class="fa fa-check-square"></i>工单列表</a></div>
            </li>
            @endif
            <li>
                <div class="link"><a href="{{ url('admin/problem') }}"  ><i class="fa fa-file-text-o "></i>回馈问题</a></div>
            </li>
            @if(isset($webType) && $webType == 2)
            <li>
                <div class="link"><i class="fa fa-address-card-o"></i><a href="{{ url('admin/customer') }}">客户管理</a></div>
            </li>
            @endif
            <li>
                <div class="link"><i class="fa fa-user-o"></i><a href="{{ url('admin/staff') }}">员工信息</a></div>
            </li>
            <li><div class="link"><i class="fa fa-book"></i>知识管理<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/lore') }}">知识库</a></li>
                    <li><a href="{{ url('admin/lore_type') }}">知识分类</a></li>
                </ul>
            </li>
            @if(isset($webType) && $webType == 2)
            <li><div class="link"><i class="fa fa-hourglass-start "></i>在线考试<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/subject_type') }}">第一步：试题分类</a></li>
                    <li><a href="{{ url('admin/subject') }}">第二步：试题管理</a></li>
                    <li><a href="{{ url('admin/paper') }}">第三步：试卷生成</a></li>
                    <li><a href="{{ url('admin/exam') }}">第四步：考次管理</a></li>
                </ul>
            </li>
            <li><div class="link"><i class="fa fa-bar-chart"></i>数据统计<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/count_call') }}"> 来电统计</a></li>
                    <li><a href="{{ url('admin/count_repair') }}"> 维修数据</a></li>
                    <li><a href="{{ url('admin/count_customer') }}"> 客户数据</a></li>
                </ul>
            </li>
            @endif
            <li><div class="link"><i class="fa fa-cog "></i>系统设置<i class="fa fa-chevron-down"></i></div>
                <ul class="submenu">
                    <li><a href="{{ url('admin/department') }}">部门管理</a></li>
                    <li><a href="{{ url('admin/position') }}">职位</a></li>
                    @if(isset($webType) && $webType == 2)
                    <li><a href="{{ url('admin/customer_type') }}">客户分类</a></li>
                    <li><a href="{{ url('admin/work_caller_type') }}">来电分类</a></li>
                    <li><a href="{{ url('admin/work_type') }}">工单分类</a></li>
                    @endif
                    <li><a href="{{ url('admin/problem_type') }}">反馈分类</a></li>
                    @if(isset($webType) && $webType == 2)
                    <li><a href="{{ url('admin/tags') }}">业务标签</a></li>
                    @endif
                    <li><a href="{{ url('admin/area') }}">区县划分</a></li>
                    {{--<li><a href="{{ url('admin/roles') }}">角色权限</a></li>--}}
                    <li><a href="{{ url('admin/site_admin') }}">管理员</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<script>
$(function(){
    var lanren = $(".lanren a");
    lanren.click(function(){
        $(this).addClass("thisclass").siblings().removeClass("thisclass");
    });
});
</script>
class="thisclass"