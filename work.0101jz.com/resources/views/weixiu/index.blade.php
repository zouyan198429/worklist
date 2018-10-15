@extends('layouts.weixiu')

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
		</div>
	</div>
	<div class="mm">

		<h3>最新公告</h3>
		<div class="row" >
				<div class="col-xs-12">
					<ul class="indgg">
						@foreach ($noticeList as $notice)
						<li><span class="date">{{ $notice['created_at_ym'] or '' }}</span><div class="title" onclick="otheraction.show({{ $notice['id'] or '' }})">{{ $notice['title'] or '' }}</div></li>
						@endforeach
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
        var SATUS_COUNT_URL = "{{ url('api/weixiu/work/ajax_status_count') }}";// ajax工单状态统计 url
        var NEED_PLAY_STATUS = "{{ $countPlayStatus }}";// 需要发声的状态，多个逗号,分隔

        var NOTICE_SHOW_URL = "{{url('weixiu/notice/info/')}}/";//显示页面地址前缀 + id

	</script>
	<script src="{{ asset('js/weixiu/lanmu/index.js') }}"  type="text/javascript"></script>
@endpush