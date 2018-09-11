@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">在线考试</div>
		</div>
		<section class="main" id="study" >



			<div class="hd tab">
				<a href="{{ url('m/exam') }}" class="on">近期考试</a>
				<a href="{{ url('m/exam_score') }}">成绩查询</a>
			</div>
			<div class="bd">
				<ul class="boxlist">
					<li>

						<h3>维修业务知识测评</h3>
						<dl>
							<dt>开考日期</dt>
							<dd>2018-05-21</dd>
						</dl>
						<dl>
							<dt>考试时间</dt>
							<dd>09:30--11:00</dd>
						</dl>
						<dl>
							<dt>考试时长</dt>
							<dd>90分钟</dd>
						</dl>
						<dl>
							<dt>考试岗位</dt>
							<dd>话务</dd>
						</dl>
						<div class="c"></div>
						<div class="btnbox"><button ><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 距离开考：2天3小时33分钟</button></div>
					</li>
					<li>

						<h3>维修业务知识测评</h3>
						<dl>
							<dt>开考日期</dt>
							<dd>2018-05-21</dd>
						</dl>
						<dl>
							<dt>考试时间</dt>
							<dd>09:30--11:00</dd>
						</dl>
						<dl>
							<dt>考试时长</dt>
							<dd>90分钟</dd>
						</dl>
						<dl>
							<dt>考试岗位</dt>
							<dd>话务</dd>
						</dl>
						<div class="c"></div>
						<div class="btnbox"><button ><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 距离开考：2天3小时33分钟</button></div>
					</li>




				</ul>
			</div>

		</section>
		@include('mobile.layout_public.menu', ['menu_id' => 3])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush