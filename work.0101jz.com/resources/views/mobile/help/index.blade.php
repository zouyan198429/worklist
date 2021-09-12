@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">帮助中心</div>
		</div>
		<section class="main" id="help" style="text-align: left;" >
		@if(isset($webType) && $webType == 2)
                @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 8) == 8)
                <h2>如何查看工单？</h2>
                <div class="con">
                    登录系统后，点击屏幕左侧主菜单：我的工单，即可进入工单列表页面。<br />
                    工单状态分为：<br />
                    1. 待确认  新接收到的工单需要点击确认按钮。<br />
                    2. 处理中  确认后的工单进入处理环节，状态为处理中。<br />
                    3. 过期未处理  处理完成的工单点击结单，在弹窗下方填写处理结果。超过要求时间未结单的状态为过期未处理。<br />
                    4. 待回访  处理完成的订单进入回访环节。回访完成后要点击详情按钮进入弹窗，填写回访记录。<br />
                    5. 完成 回访完成后的订单为完成订单。
                </div>
                <br />
                @endif
		@endif

            @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 4) == 4)
 		<h2>如何反馈问题？</h2>
 		<div class="con">
 			登录系统后，点击屏幕左侧主菜单：反馈问题，即可进入反馈问题页面。<br />
 			进入页面后，选择问题类型及反馈到哪个部门，再填写问题详细描述，点击提交即可。
 		</div>
 		<br />
            @endif
            @if( isset($baseArr ['account_type']) && $baseArr ['account_type'] != 2)
 		<h2>如何修改密码？</h2>
 		<idv class="con">
 			新登录用户建议在第一次登录后即修改掉原始登录密码。<br />
 			将鼠标移到屏幕右上角部门名称或个人姓名处，即弹出隐藏的二级菜单，点击修改密码项即可进入密码修改页面。根据提示输入新密码即可，新密码须6位以上，含英文及数字，建议首字母大写。

 		</idv>
            @endif

		</section>
		@include('mobile.layout_public.menu', ['menu_id' => 2])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush
