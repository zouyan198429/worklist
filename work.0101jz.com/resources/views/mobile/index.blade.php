
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
			<ul>
				@foreach ($msgList as $msg)
				<li class="item">
					<div class="con">  <i class="fa fa-bell-o  fa-fw" aria-hidden="true"></i>  {{ $msg['mst_content'] or '' }}</div>
			 		<div class="btnbox2"><a href="#" class="btn smg_sure" data-id="{{ $msg['id'] or '' }}">收到</a></div>
			 		<div class="c"></div>
			 	</li>
				@endforeach
			</ul>

		</div>

		<div class="line10"></div>
		<div class="box" id="dynamic-table">
			<div class="tab">
                <a href="javascript:void(0);" class="on status_click" data-status="1">待确认({{ $waitSureCount or 0 }})</a>
				<a href="javascript:void(0);" class=" status_click"  data-status="2">待处理({{ $doingCount or 0 }})</a>
				<a href="javascript:void(0);" class=" status_click" data-status="4,8">已完成</a>

                <form style="display: none;" onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
                    <input type="text" name="status" value="">
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
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
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


</script>
 <script src="{{asset('js/common/list.js')}}"></script>
 <script src="{{asset('js/m/lanmu/index.js')}}"></script>
@endpush



