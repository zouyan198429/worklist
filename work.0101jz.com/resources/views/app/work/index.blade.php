@extends('layouts.app')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<section id="mainnav" >
		<div class="gd-list" >
			<div class="gd-hd">
				<span>工单号：34523 </span>
				<span>下单时间：05-22  15:33</span>
				<span>工单等级：2小时</span>
			</div>
			<div class="gd-bd">
				<span>客户姓名：王(女)</span>
				<span>类别：企业 <br /></span>
				<span>位置：秦州区/中城街道</span>
			</div>
			<div class="gd-fd">
				剩余时间：1小时12分
				<a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a>
				<a href="" class="btn" >结单</a>
			</div>
		</div>
		<div class="gd-list" >
			<div class="gd-hd">
				<span>工单号：34523 </span>
				<span>下单时间：05-22  15:33</span>
				<span>工单等级：2小时</span>
			</div>
			<div class="gd-bd">
				<span>客户姓名：王(女)</span>
				<span>类别：企业 <br /></span>
				<span>位置：秦州区/中城街道</span>
			</div>
			<div class="gd-fd">
				剩余时间：1小时12分
				<a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a>
				<a href="" class="btn" >结单</a>
			</div>
		</div>
		<div class="gd-list" >
			<div class="gd-hd">
				<span>工单号：34523 </span>
				<span>下单时间：05-22  15:33</span>
				<span>工单等级：2小时</span>
			</div>
			<div class="gd-bd">
				<span>客户姓名：王(女)</span>
				<span>类别：企业 <br /></span>
				<span>位置：秦州区/中城街道</span>
			</div>
			<div class="gd-fd">
				剩余时间：1小时12分
				<a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a>
				<a href="" class="btn" >结单</a>
			</div>
		</div>
		<div class="gd-list" >
			<div class="gd-hd">
				<span>工单号：34523 </span>
				<span>下单时间：05-22  15:33</span>
				<span>工单等级：2小时</span>
			</div>
			<div class="gd-bd">
				<span>客户姓名：王(女)</span>
				<span>类别：企业 <br /></span>
				<span>位置：秦州区/中城街道</span>
			</div>
			<div class="gd-fd">
				剩余时间：1小时12分
				<a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a>
				<a href="" class="btn" >结单</a>
			</div>
		</div>
		<div class="gd-list" >
			<div class="gd-hd">
				<span>工单号：34523 </span>
				<span>下单时间：05-22  15:33</span>
				<span>工单等级：2小时</span>
			</div>
			<div class="gd-bd">
				<span>客户姓名：王(女)</span>
				<span>类别：企业 <br /></span>
				<span>位置：秦州区/中城街道</span>
			</div>
			<div class="gd-fd">
				剩余时间：1小时12分
				<a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a>
				<a href="" class="btn" >结单</a>
			</div>
		</div>

		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pages">
				<a href="" class="on" > < </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 3 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
			</div>
		</div>
	</section>

@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script src="{{asset('app/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>
@endpush