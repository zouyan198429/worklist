
<div id="footnav">
    @if(isset($webType) && $webType == 2)
    <a href="{{ url('m') }}" class="a1 @if ( $menu_id == 1) on @endif"><i><img src="http://pic.0101jz.com/yd-icon-home2.svg" alt=""></i><span>首页</span></a>
    @endif
    <a href="{{ url('m/lore') }}" class="a2 @if ( $menu_id == 2) on @endif"><i><img src="http://pic.0101jz.com/yd-icon-study2.svg" alt=""></i><span>学习</span></a>
    @if(isset($webType) && $webType == 2)
    <a href="{{ url('m/exam') }}" class="a3 @if ( $menu_id == 3) on @endif"><i><img src="http://pic.0101jz.com/yd-icon-kaoshi2.svg" alt=""></i><span>考试</span></a>
    @endif
    <a href="{{ url('m/problem/add/0') }}" class="a4 @if ( $menu_id == 4) on @endif"><i><img src="http://pic.0101jz.com/yd-icon-feedback2.svg" alt=""></i><span>反馈</span></a>
    <a href="{{ url('m/staff') }}" class="a5  @if ( $menu_id == 5) on @endif"><i><img src="http://pic.0101jz.com/yd-icon-myinfo2.svg" alt=""></i><span>我的</span></a>
</div>