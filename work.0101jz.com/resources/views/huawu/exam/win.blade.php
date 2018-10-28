@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>

	<div class="mm" id="examinbox">
		{{--<div class="mmhead" >--}}
			{{--考试完成--}}
		{{--</div>--}}
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
			<a href="{{ url('huawu/exam') }}" class="btn" > 返回  </a>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush