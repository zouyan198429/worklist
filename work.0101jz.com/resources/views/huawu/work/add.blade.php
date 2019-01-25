@extends('layouts.huawu')
@push('preheadscripts')
	{{--  本页单独使用 --}}
	<!-- zui css -->
	<link rel="stylesheet" href="{{asset('dist/css/zui.min.css') }}">
@endpush
@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的客户</div>
	<div class="mm">
		<div class="alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<p>一次最多上传9张图片。</p>
		</div>
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>上传图片</th>
				<td>
					<div class="row  baguetteBoxOne gallery ">
						<div class="col-xs-6">
							@component('component.upfileone.piconecode')
								@slot('fileList')
									grid
								@endslot
								@slot('upload_url')
									{{ url('api/huawu/upload') }}
								@endslot
							@endcomponent
							{{--
                            <input type="file" class="form-control" value="">
                            --}}
						</div>
					</div>

				</td>
			</tr>
			<tr>
				<th>来电号码<span class="must">*</span></th>
				<td>
					<input type="number" class="inp wnormal" name="call_number" value="{{ $call_number or '' }}" placeholder="来电号码" autofocus  required />
					<input type="number" class="inp wnormal" name="contact_number" value="{{ $contact_number or '' }}" placeholder="联系电话"  />
				</td>
			</tr>

			<tr>
				<th>投诉类型<span class="must">*</span></th>
				<td>
					<select class="wnormal" name="caller_type_id">
						<option value="">请选择来电类型</option>
						@foreach ($workCallTypeList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($caller_type_id) && $caller_type_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
				</td>
			</tr>

			<tr>
				<th>业务类型</th>
				<td>

					<select class="wnormal" name="work_type_id">
						<option value="">请选择类型</option>
						@foreach ($workFirstList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($work_type_id) && $work_type_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wnormal" name="business_id">
						<option value="">请选择业务</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>工单内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong" name="content" />{{ $content or '' }}</textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
				</td>
			</tr>
			<tr  style="display: none;" >
				<th> </th>
				<td>
					@foreach ($serviceTagList as $k=>$txt)
						<a class="tags"><label><input type="radio" name="tag_id" value="{{ $k }}"  @if(isset($tag_id) && $tag_id == $k) checked="checked"  @endif>{{ $txt }} </label></a>
					@endforeach

				</td>
			</tr>

			<tr>
				<th></th>
				<td>
					@foreach ($serviceTimeList as $k=>$txt)
						<label><input type="radio"  name="time_id"  value="{{ $k }}"  @if(isset($time_id) && $time_id == $k) checked="checked"  @endif />{{ $txt }} </label>
					@endforeach
				</td>
			</tr>

			<tr style="display: none;">
				<th>预约处理时间</th>
				<td>
					<input type="text" id="yuyuetime" name="book_time" class="inp wlong form-date" value="{{ $book_time or '' }}" />
					{{--
					<datalist id="yuyuetime" style="display:none" >
						<option value="今天">今天</option>
						<option value="明天">明天</option>
						<option value="后天">后天</option>
					</datalist>
					--}}
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1"  style="display: none;">
			<tr>
				<th>客户姓名</th>
				<td>
					<input type="text" class="inp wlong" name="customer_name" value="{{ $customer_name or '' }}"/>
				</td>
			</tr>
			<tr>
				<th>客户性别</th>
				<td>
					<label><input type="radio" name="sex" value="1" @if (isset($sex) && $sex == 1 ) checked @endif>男</label>&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input type="radio" name="sex" value="2" @if (isset($sex) && $sex == 2 ) checked @endif>女</label>
				</td>
			</tr>
			<tr>
				<th>客户类别</th>
				<td>
					@foreach ($customerTypeList as $k=>$txt)
						<label><input type="radio"  name="type_id"  value="{{ $k }}"  @if(isset($type_id) && $type_id == $k) checked="checked"  @endif />{{ $txt }} </label>
					@endforeach
				</td>
			</tr>
		</table>

		<table class="table1">

			<tr>
				<th>客户地址</th>
				<td>
					<select class="wmini" name="city_id">
						<option value="">请选择区县</option>
						@foreach ($areaCityList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($city_id) && $city_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wmini" name="area_id">
						<option value="">请选择街道</option>
					</select>
					<input type="text" class="inp wnormal"  name="address" value="{{ $address or '' }}"/>
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">
			<tr>
				<th>派发到</th>
				<td>
					<span class="send_real_name">{{ $send_department_name or '' }} {{ $send_group_name or '' }} {{ $send_real_name or '' }}</span>
					<input type="hidden" name="send_staff_id"  value="{{ $send_staff_id or '' }}" />
					<input type="hidden" name="send_staff_history_id"  value="{{ $send_staff_history_id or '' }}" />
					<button type="button"   class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectSendStaff(this)">选择同事</button>

					{{--<button type="button"   class="btn btn-danger  btn-xs ace-icon fa fa-pencil bigger-60 update_send_staff" @if(isset($now_send_staff) && in_array($now_send_staff,[0,1])) style="display: none;"  @endif  onclick="otheraction.updateSendStaff(this)">更新[当前同事已更新]</button>--}}

					{{--
					<select class="wnormal" name="send_department_id">
						<option value="">请选择部门</option>
						@foreach ($departmentFirstList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($send_department_id) && $send_department_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wnormal" name="send_group_id">
						<option value="">请选择小组</option>
					</select>
					<select class="wnormal" name="send_staff_id">
						<option value="">请选择员工</option>
					</select>
					--}}
					{{--<p class="tip">客户所在区街道和责任员工相对应</p>---}}
				</td>
			</tr>

			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
			</tr>

		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">

        const SAVE_URL = "{{ url('api/huawu/work/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('huawu/work')}}";//保存成功后跳转到的地址
        const DEPARTMENT_CHILD_URL = "{{ url('api/huawu/department/ajax_get_child') }}";// 部门二级分类请求地址
        const GROUP_CHILD_URL = "{{ url('api/huawu/staff/ajax_get_child') }}";// 部门组获得员工---二级分类请求地址
        const WORKTYPE_CHILD_URL = "{{ url('api/huawu/work_type/ajax_get_child') }}";// 维修类型二级分类请求地址
        const AREA_CHILD_URL = "{{ url('api/huawu/area/ajax_get_child') }}";// 区县二级分类请求地址
        const BOOK_TIME = "{{ $book_time or '' }}" ;//预约处理时间
        const WORK_TYPE_ID = "{{ $work_type_id or 0}}";// 维修类型-默认值
        const BUSINESS_ID = "{{ $business_id or 0 }}";// 维修类型二级--默认值
        const CITY_ID = "{{ $city_id or 0}}";// 县区id默认值
        const AREA_ID = "{{ $area_id or 0 }}";// 街道默认值
        {{--const SEND_DEPARTMENT_ID = "{{ $send_department_id or 0}}";// 部门默认值--}}
        {{--const SEND_GROUP_ID = "{{ $send_group_id or 0 }}";// 小组默认值--}}
        {{--const SEND_STAFF_ID = "{{ $send_staff_id or 0 }}";// 指派员工默认值--}}

        var SELECT_SEND_STAFF_URL = "{{ url('huawu/staff/select') }}";// 选择派送到员工地址
        var AJAX_SEND_STAFF_ADD_URL = "{{ url('api/huawu/staff/ajax_add_staff_single') }}";// ajax添加/修改派送到员工地址

		// 上传图片变量
        var FILE_UPLOAD_URL = "{{ url('api/huawu/upload') }}";// 文件上传提交地址 'your/file/upload/url'
        var PIC_DEL_URL = "{{ url('api/huawu/upload/ajax_del') }}";// 删除图片url
        var MULTIPART_PARAMS = {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
		var LIMIT_FILES_COUNT = 9;//   限制文件上传数目	false（默认）或数字
        var MULTI_SELECTION = true;//  是否可用一次选取多个文件	默认 true false
        var FLASH_SWF_URL = "{{asset('dist/lib/uploader/Moxie.swf') }}";// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
        var SILVERLIGHT_XAP_URL = "{{asset('dist/lib/uploader/Moxie.xap') }}";// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
        var SELF_UPLOAD = true;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
        var FILE_UPLOAD_METHOD = 'initPic()';// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
        var FILE_UPLOAD_COMPLETE = '';  // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
        var FILE_RESIZE = {quuality: 40};
        // resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
        //     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
        //     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
        //     // crop: true,// 是否对图片进行裁剪；
        //     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
        //     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
        // },
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
            @if (isset($city_id) && $city_id >0 )
                changeFirstSel(REL_CHANGE.area_city,CITY_ID,AREA_ID, false);
            @endif

            //当前部门小组
            {{--@if (isset($send_department_id) && $send_department_id >0 )--}}
                {{--changeFirstSel(REL_CHANGE.department,SEND_DEPARTMENT_ID,SEND_GROUP_ID, false);--}}

                {{--// 当前的员工--}}
                {{--@if (isset($send_group_id) && $send_group_id >0 )--}}
					{{--var send_department_id = $('select[name=send_department_id]').val();--}}
					{{--var tem_config = REL_CHANGE.staff_department;--}}
					{{--tem_config.other_params = {'department_id':send_department_id};--}}
                    {{--changeFirstSel(tem_config,SEND_GROUP_ID,SEND_STAFF_ID, false);--}}
                {{--@endif--}}
            {{--@endif--}}

        });
	</script>
	<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
	<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
	{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}

	<!-- zui js -->
	<script src="{{asset('dist/js/zui.min.js') }}"></script>
	<script src="{{ asset('/js/huawu/lanmu/work_edit.js') }}"  type="text/javascript"></script>
	@component('component.upfileincludejs')
	@endcomponent
@endpush