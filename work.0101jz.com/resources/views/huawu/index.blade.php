@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 首页</div>
	<div class="mm">
		<h2>信息总揽</h2>
		<div class="row" >
			@foreach ($status as $k=>$txt)
				<div class="col-sm">
					@if(in_array($k,$countStatus))
						<p class="layui-badge status_count_{{ $k }}" data-old_count="0">0</p>
					@endif
					<h4>{{ $txt }}</h4>
				</div>
			@endforeach
			{{--
			<div class="col-sm">
				<p>0</p>
				<h4>今日受理工单</h4>
			</div>
			--}}
		</div>


	</div>

	<div class="mm">
		<h3>最新公告</h3>		
		<div class="row" >
				<div class="col-xs-12">
					<ul class="indgg">
						<li><span class="date">10-22</span><div class="title">公告标题</div></li>
					</ul>
				</div>
		</div>
	</div>

	<div style="display:none;">
		@include('public.scan_sound')
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var SATUS_COUNT_URL = "{{ url('api/huawu/work/ajax_status_count') }}";// ajax工单状态统计 url
        var NEED_PLAY_STATUS = "{{ $countPlayStatus }}";// 需要发声的状态，多个逗号,分隔

	</script>
	<script src="{{ asset('js/huawu/lanmu/index.js') }}"  type="text/javascript"></script>
@endpush