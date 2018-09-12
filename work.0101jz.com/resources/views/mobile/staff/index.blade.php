@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">个人中心</div>
		</div>
		<div class="infohd">
			<div id="infotx"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> </div>
			<div id="infoname">
				<h2>{{ $real_name or '' }} <span> 工号：{{ $work_num or '' }}</span></h2>
				<h4>部门：{{ $department_name or '' }}/
					{{ $group_name or '' }}</h4>
			</div>
			<div class="c"></div>
		</div>
		<section class="wrap" id="study" >
			<div class="myachieve">
				<dl>
					<dt>今日工单</dt>
					<dd>6</dd>
				</dl>
				<dl>
					<dt>本周工单</dt>
					<dd>66</dd>
				</dl>
				<dl>
					<dt>本月工单</dt>
					<dd>211</dd>
				</dl>
				<div class="c"></div>

			</div>


			<div class="mynav">
				<ul>
					<li>
						<a href="{{ url('m/password') }}">
							<span>修改密码</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					<li>
						<a href="{{ url('m/staff/list') }}">
							<span>我的同事</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					<li>
						<a href="{{ url('m/logout') }}"><span>退出</span></a>
						<div class="c"></div>
					</li>

				</ul>
			</div>
		</section>

		@include('mobile.layout_public.menu', ['menu_id' => 5])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush