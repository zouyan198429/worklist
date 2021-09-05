@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">考试成绩</div>
		</div>
		<section class="main" id="study" >



			<div class="hd tab">
				<a href="{{ url('m/exam') }}">近期考试</a>
				<a href="{{ url('m/exam_score') }}" class="on">成绩查询</a>
			</div>
			<div class="bd">
				<ul class="listtext2">
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
					<li>
						<a href="{{ url('m/exam_search') }}" >
							<div class="title" >
								<p><i class="fa fa-graduation-cap fa-fw" aria-hidden="true"></i> 维修业务知识测评</p>
								<span>2018-05-22</span>
							</div>
							<div class="btn">成绩查询</div>
							<div class="c"></div>
						</a>
					</li>
				</ul>
			</div>
			<div class="mmfoot">
				<div class="mmfleft"></div>
				<div class="mmfright pages">
					<a href="" class="on" > < </a>
					<a href="" > 1 </a>
					<a href="" > 2 </a>
					<a href=""> 3 </a>
					<a href=""> 4 </a>
					<a href=""> 5 </a>
					<a href=""> > </a>
				</div>
		</section>
		@include('mobile.layout_public.menu', ['menu_id' => 1])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush
