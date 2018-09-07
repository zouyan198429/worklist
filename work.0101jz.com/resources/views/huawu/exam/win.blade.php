@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>

	<div class="mm" id="examinbox">
		<div class="mmhead" >
			考试完成
		</div>
		<div class="content tc">
			<h1>恭喜您答题完成！</h1>
			<p>
				共完成试题：50; <br />
				正确：42；<br />
				用时：80分钟；<br />
				得分：<span class="red f48">84</span>分；
			</p>

		</div>

		<div class="mmfoot tc">
			<a href="{{ url('huawu/exam/index') }}" class="btn" > 返回  </a>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush