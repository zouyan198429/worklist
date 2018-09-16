
@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div class="logo">
			<img src="http://ofn8u9rp0.bkt.clouddn.com/logo-ydapp3.png" alt="移动工单管理系统">
		</div>
		<div class="box" id="indmess">
			<ul>
				@foreach ($msgList as $msg)
				<li class="item">
					<div class="con">  <i class="fa fa-bell-o  fa-fw" aria-hidden="true"></i>  {{ $msg['mst_content'] or '' }}</div>
			 		<div class="btnbox2"><a href="#" class="btn" data-id="{{ $msg['id'] or '' }}">收到</a></div>
			 		<div class="c"></div>
			 	</li>
				@endforeach
			</ul>

		</div>

		<div class="line10"></div>


		<div class="box">
			<div class="tab">
				<a href="#" class="on">待处理({{ count($waitWorkList) }})</a>
				<a href="#">已完成</a>
			</div>
			<div class="bd">
				@foreach ($waitWorkList as $work)
				<div class="gd-list" >
					<div class="gd-hd">
						<p>
							<span class="khname"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> {{  $work['customer_name'] or '' }}({{  $work['sex_text'] or '' }}) </span>
							<a href="tel:{{  $work['call_number'] or '' }}" class="btnnb fr" ><i class="fa fa-phone fa-fw" aria-hidden="true"></i> {{  $work['call_number'] or '' }}  </a>
					</div> 
					<div class="gd-bd">
						<p><i class="fa fa-flag fa-fw" aria-hidden="true"></i>  工单类型：{{  $work['type_name'] or '' }}--{{  $work['business_name'] or '' }}</p>
						<p class="khtip">{!!  $work['content'] or ''  !!}
						</p>
		 				<p>
							<span class="gdtime"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 报修时间：{{  judgeDate($work['created_at'], 'm-d H:i:s')  }}</span>
							<span class="gdtime"> 预约时间：{{  judgeDate($work['book_time'], 'm-d H:i:s')  }} </span>
						</p>
					</div>
					<div class="gd-fd">
					  <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i> {{  $work['city_name'] or '' }}{{  $work['area_name'] or '' }}{{  $work['address'] or '' }} </p>
					</div>
					<div class="btnbox">				
						<a href="" class="btn fr" data-id="{{  $work['id'] or '' }}" >结单</a>
						<div class="c"></div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@include('mobile.layout_public.menu', ['menu_id' => 1])
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush



