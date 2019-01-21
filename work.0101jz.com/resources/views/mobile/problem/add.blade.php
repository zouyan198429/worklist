@extends('layouts.m')
@push('preheadscripts')
	{{--  本页单独使用 --}}
	<!-- zui css -->
	<link rel="stylesheet" href="{{asset('dist/css/zui.min.css') }}">
@endpush
@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-title">问题反馈</div>
		</div>
		<div id="prlblem" class="table4" >
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p>一次最多上传9张图片。</p>
			</div>
			<form class="am-form am-form-horizontal" method="post"  id="addForm">
				<input type="hidden" name="id" value="{{ $id or 0 }}"/>
				<dl class="inp">
					<dt></dt>
					<dd>
						<div class="row  baguetteBoxOne gallery ">
							<div class="col-xs-6">
								@component('component.upfileone.piconecode')
									@slot('fileList')
										grid
									@endslot
									@slot('upload_url')
										{{ url('api/m/upload') }}
									@endslot
								@endcomponent
								{{--
                                <input type="file" class="form-control" value="">
                                --}}
							</div>
						</div>
					</dd>
				</dl>
			<dl class="inp">

				<dt>问题类型</dt>
				<dd>
					<select class="wnormal" name="work_type_id" >
						<option  value="">请选择问题</option>
						@foreach ($problemFirstList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($work_type_id) && $work_type_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wnormal" name="business_id" >
						<option value="">请选择部门</option>
					</select>
				</dd>
			</dl>
			<dl class="inp">
				<!-- 					<dt>反馈内容<span class="must">*</span></dt>
                 -->					<dd>
					<textarea type="text" class="inptext wlong" name="content"  style=" height:200px"  placeholder="反馈内容" />{{ $content or '' }}</textarea>
				</dd>
			</dl>
			<div class="k10"></div>
			<div class="line"></div>
			<div class="k10"></div>
			<dl class="inp" style="display: none;">
				<!-- 					<dt>客户电话</dt>
                 -->					<dd>
					<input type="text" class="inp wlong" value="{{ $call_number or '' }}" name="call_number" placeholder="客户电话" autofocus  required />
				</dd>
			</dl>
			<dl class="inp" style="display: none;">
				<dt>客户地址</dt>
				<dd>
					<select class="wnormal" name="city_id" >
						<option value="">请选择区县</option>
						@foreach ($areaCityList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($city_id) && $city_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wnormal"  name="area_id">
						<option value="">请选择街道</option>
					</select>
					<div class="k10"></div>
					<input type="text" class="inp wlong" name="address"  placeholder="详细地址"   value="{{ $address or '' }}" />
				</dd>
			</dl>
			<dl>
				<dt> </dt>
				<div class="k10"></div>
				<dd><button class="btn btn-l wlong"  id="submitBtn"  >提交</button>
				</dd>
			</dl>
			</form>
		</div>

		@include('mobile.layout_public.menu', ['menu_id' => 4])


	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	{{--<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>--}}
	<script src="{{asset('staticmobile/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>
	<script type="text/javascript">
        const SAVE_URL = "{{ url('api/m/problem/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('m/problem/add/0')}}";//保存成功后跳转到的地址
        const WORKTYPE_CHILD_URL = "{{ url('api/m/problem_type/ajax_get_child') }}";// 维修类型二级分类请求地址
        const AREA_CHILD_URL = "{{ url('api/m/area/ajax_get_child') }}";// 区县二级分类请求地址
        const WORK_TYPE_ID = "{{ $work_type_id or 0}}";// 维修类型-默认值
        const BUSINESS_ID = "{{ $business_id or 0 }}";// 维修类型二级--默认值
        const CITY_ID = "{{ $city_id or 0}}";// 县区id默认值
        const AREA_ID = "{{ $area_id or 0 }}";// 街道默认值

        // 上传图片变量
        var FILE_UPLOAD_URL = "{{ url('api/m/upload') }}";// 文件上传提交地址 'your/file/upload/url'
        var PIC_DEL_URL = "{{ url('api/m/upload/ajax_del') }}";// 删除图片url
        var MULTIPART_PARAMS = {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
        var LIMIT_FILES_COUNT = 9;//   限制文件上传数目	false（默认）或数字
        var MULTI_SELECTION = true;//  是否可用一次选取多个文件	默认 true false
        var FLASH_SWF_URL = "{{asset('dist/lib/uploader/Moxie.swf') }}";// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
        var SILVERLIGHT_XAP_URL = "{{asset('dist/lib/uploader/Moxie.xap') }}";// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
        var SELF_UPLOAD = true;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
        var FILE_UPLOAD_METHOD = 'initPic()';// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
        var FILE_UPLOAD_COMPLETE = '';  // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
        var RESOURCE_LIST = @json($resource_list) ;
        var PIC_LIST_JSON =  {'data_list': RESOURCE_LIST };// piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}

        $(function(){
			{{-- 九张图片上传--}}
			{{--@include('component.upfileone.piconejsinitincludenine', ['submit_url' => url('api/huawu/upload')])--}}
            // 当前的维修类型
			@if (isset($work_type_id) && $work_type_id >0 )
            	changeFirstSel(REL_CHANGE.work_type,WORK_TYPE_ID,BUSINESS_ID, false);
			@endif

            // 当前的客户地址
			{{--@if (isset($city_id) && $city_id >0 )--}}
            	{{--changeFirstSel(REL_CHANGE.area_city,CITY_ID,AREA_ID, false);--}}
			{{--@endif--}}
        });
	</script>
	<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
	<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
	{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}

	<!-- zui js -->
	<script src="{{asset('dist/js/zui.min.js') }}"></script>
	<script src="{{ asset('/js/m/lanmu/problem_edit.js') }}"  type="text/javascript"></script>
	@component('component.upfileincludejs')
	@endcomponent
@endpush
