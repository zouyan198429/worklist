@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
<style type="text/css">
	.right { color: green;font-weight: bold;}
	.wrong {color: red;font-weight: bold;}
</style>
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">在线考试</div>
		</div>
		@include('common.pageParams')
		<section class="main" id="study" >

			<div class="content tc">
				<h1>恭喜您答题完成！</h1>
				<p>
					共完成试题：{{ $do_num or '' }}; <br />
					正确：{{ $wight_num or '' }}；<br />
					用时：{{ $do_time_mimute or '' }}分{{ $do_time_second or '' }}秒；<br />
					得分：<span class="red f48">{{ $exam_results or '' }}</span>分({{ $pass_text or '' }})；
				</p>

			</div>

			<div class="mmfoot tc">
				<a href="{{ url('m/exam_score') }}" class="btn" > 返回  </a>
			</div>

		</section>
		@include('mobile.layout_public.menu', ['menu_id' => 3])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush