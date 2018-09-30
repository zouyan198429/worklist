
@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

    @include('common.pageParams')

	<div class="page">

		<div class="logo">
			<img src="http://ofn8u9rp0.bkt.clouddn.com/logo-ydapp3.png" alt="移动工单管理系统">
		</div>
		<div class="box" id="indmess">
			<ul id="msgList" data-old_msg="0">
			</ul>

		</div>

		<div class="line10"></div>
		<div class="box" id="dynamic-table">
			<div class="tab">
				@foreach ($status as $k=>$txt)
					<a href="javascript:void(0);" data-status="{{ $k }}"  class=" status_click  @if ($k == $defaultStatus) on @endif" >
						{{ $txt }}
						@if(in_array($k,$countStatus))
							(<span class="status_count_{{ $k }}" data-old_count="0">0</span>)
						@endif
					</a>
				@endforeach
				<a href="javascript:void(0);" class=" status_click" data-status="4,8">已完成</a>

                <form style="display: none;" onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
					<select style="width:80px; height:28px;" name="status" >
						<option value="">全部</option>
						@foreach ($status as $k=>$txt)
							<option value="{{ $k }}" @if ($k == $defaultStatus) selected @endif >{{ $txt }}</option>
						@endforeach
						<option value="4,8">已完成</option>
					</select>
                    <button class="btn btn-normal  search_frm ">搜索</button>
                </form>
			</div>

			<div class="bd"   id="data_list">


			</div>
            <div class=" bd mmfoot">
                <div class="mmfleft"></div>
                <div class=" mmfright pagination">
                </div>
            </div>
		</div>
		@include('mobile.layout_public.menu', ['menu_id' => 1])
	 </div>
	<div style="display:none;">
		@include('public.scan_sound')
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
        const AJAX_URL = "{{ url('api/m/work/ajax_doing_list') }}";//ajax请求的url
        {{--const ADD_URL = "{{ url('m/work/add/0') }}"; //添加url--}}
        {{--const SHOW_URL = "{{url('m/work/info/')}}/";//显示页面地址前缀 + id--}}
        {{--const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示--}}
        {{--const EDIT_URL = "{{url('m/work/add/')}}/";//修改页面地址前缀 + id--}}
        {{--const DEL_URL = "{{ url('api/m/work/ajax_del') }}";//删除页面地址--}}
        {{--const BATCH_DEL_URL = "{{ url('api/m/work/ajax_del') }}";//批量删除页面地址--}}
        {{--const EXPORT_EXCEL_URL = "{{ url('m/work/add/0') }}"; //"{{ url('api/m/work/export') }}";//导出EXCEL地址--}}
        {{--const IMPORT_EXCEL_URL = "{{ url('m/work/add/0') }}"; //"{{ url('api/m/work/import') }}";//导入EXCEL地址--}}

    const SURE_MSG_URL = "{{ url('api/m/msg/ajax_save') }}/";// ajax确认消息地址
    const SURE_WORK_URL = "{{ url('api/m/work/ajax_sure') }}/";// ajax确认工单地址
    const WIN_WORK_URL = "{{ url('api/m/work/ajax_win') }}/";// ajax工单结单地址
    const WIN_WORK_PAGE_URL = "{{ url('m/work/win') }}/";// ajax工单结单地址

        const SATUS_COUNT_URL = "{{ url('api/m/work/ajax_status_count') }}";// ajax工单状态统计 url
        const NEED_PLAY_STATUS = "{{ $countPlayStatus }}";// 需要发声的状态，多个逗号,分隔

		const MSG_LIST_URL = "{{ url('api/m/msg/ajax_alist') }}";// ajax最新消息 url

</script>
 <script src="{{asset('js/common/list.js')}}"></script>
 <script src="{{asset('js/m/lanmu/index.js')}}"></script>
@endpush