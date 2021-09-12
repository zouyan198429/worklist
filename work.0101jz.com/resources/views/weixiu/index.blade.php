@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
<script src="{{asset('dist/lib/jquery-qrcode-master/jquery.qrcode.min.js')}}"></script>
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 首页</div>
	@if(isset($webType) && $webType == 2)
    <div class="mm"  @if( isset($baseArr ['module_no']) && ($baseArr ['module_no'] & 8) != 8) style="display: none;" @endif>
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
	@endif

    <div class="mm">

        <h2>访问地址</h2>
        <div class="row" >
            {{--            <div class="col-sm">--}}
            {{--                <p class="layui-badge">0</p>--}}
            {{--               <h4>aaaaa</h4>--}}
            {{--            </div>--}}

            <p class="web_block">
                web后台：
                <span class="web_url">{{ $webLoginUrl or '' }}</span>
                <input type="button" class="btn btn-success  btn-xs export_excel"  value="复制"  onclick="otheraction.copyWebUrl(this)">
                <br/><span class="qrcode" data-qrcodeurl="{{ $webLoginUrl or '' }}"></span>
                <br/><a href="{{ $webLoginUrl or '' }}" target="_blank" >马上登录，使用系统</a>
            </p>
            <p class="m_block">
                员工H5系统： <span class="h5_url">{{ $mLoginUrl or '' }}</span>
                <input type="button" class="btn btn-success  btn-xs export_excel"  value="复制"  onclick="otheraction.copyH5Url(this)">
                <br/><span class="qrcode" data-qrcodeurl="{{ $mLoginUrl or '' }}"></span>
                <br/><a href="{{ $mLoginUrl or '' }}" target="_blank">马上登录，使用系统</a>
            </p>
            {{--
            <div class="col-sm">
                <p>0</p>
                <h4>今日受理工单</h4>
            </div>
            --}}
        </div>


    </div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	@if(isset($webType) && $webType == 2)
	<script type="text/javascript">
        var SATUS_COUNT_URL = "{{ url('api/weixiu/work/ajax_status_count') }}";// ajax工单状态统计 url
        var NEED_PLAY_STATUS = "{{ $countPlayStatus }}";// 需要发声的状态，多个逗号,分隔

        var NOTICE_SHOW_URL = "{{url('weixiu/notice/info/')}}/";//显示页面地址前缀 + id

	</script>
	<script src="{{ asset('js/weixiu/lanmu/index.js') }}"  type="text/javascript"></script>
	@endif
@endpush
