@extends('layouts.huawu')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的客户</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">

			<tr>
				<th>来源/号码<span class="must">*</span></th>
				<td>
					<select class="wmini" name="caller_type_id">
						<option value="">请选择来电类型</option>
						@foreach ($workCallTypeList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($caller_type_id) && $caller_type_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<input type="number" class="inp wnormal" name="call_number" value="" placeholder="来电号码" autofocus  required />
				</td>
			</tr>

			<tr>
				<th>维修类型</th>
				<td>

					<select class="wnormal" name="work_type_id">
						<option value="">请选择业务类型</option>
						@foreach ($workFirstList as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($type_id) && $type_id == $k) selected @endif >{{ $txt }}</option>
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
			<tr >
				<th> </th>
				<td>
					@foreach ($serviceTagList as $k=>$txt)
						<a class="tags"><label><input type="radio" name="tag_id" value="{{ $k }}">{{ $txt }} </label></a>
					@endforeach

				</td>
			</tr>

			<tr >
				<th></th>
				<td>
					@foreach ($serviceTimeList as $k=>$txt)
						<label><input type="radio"  name="time_id"  value="{{ $k }}"  @if(isset($time_id) && $time_id == $k) checked="checked"  @endif />{{ $txt }} </label>
					@endforeach
				</td>
			</tr>
			<tr>
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
		<table class="table1">
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
		<div class="line"> </div>
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
			<tr>
				<th>派发到</th>
				<td>
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
					{{--<p class="tip">客户所在区街道和责任员工相对应</p>---}}
				</td>
			</tr>

			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">

        const SAVE_URL = "{{ url('api/huawu/work/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('huawu/work/history')}}";//保存成功后跳转到的地址
        const DEPARTMENT_CHILD_URL = "{{ url('api/huawu/department/ajax_get_child') }}";// 部门二级分类请求地址
        const GROUP_CHILD_URL = "{{ url('api/huawu/staff/ajax_get_child') }}";// 部门组获得员工---二级分类请求地址
        const WORKTYPE_CHILD_URL = "{{ url('api/weixiu/work_type/ajax_get_child') }}";// 维修类型二级分类请求地址
        const AREA_CHILD_URL = "{{ url('api/weixiu/area/ajax_get_child') }}";// 区县二级分类请求地址
        const BOOK_TIME = "{{ $book_time or '' }}" ;//预约处理时间
        const WORK_TYPE_ID = "{{ $work_type_id or 0}}";// 维修类型-默认值
        const BUSINESS_ID = "{{ $business_id or 0 }}";// 维修类型二级--默认值
        const CITY_ID = "{{ $city_id or 0}}";// 县区id默认值
        const AREA_ID = "{{ $area_id or 0 }}";// 街道默认值
        const SEND_DEPARTMENT_ID = "{{ $send_department_id or 0}}";// 部门默认值
        const SEND_GROUP_ID = "{{ $send_group_id or 0 }}";// 小组默认值
        const SEND_STAFF_ID = "{{ $send_staff_id or 0 }}";// 指派员工默认值

        $(function(){
            // 当前的维修类型
            @if (isset($work_type_id) && $work_type_id >0 )
                changeFirstSel(REL_CHANGE.work_type,WORK_TYPE_ID,BUSINESS_ID, false);
            @endif

            // 当前的客户地址
            @if (isset($city_id) && $city_id >0 )
                changeFirstSel(REL_CHANGE.area_city,CITY_ID,AREA_ID, false);
            @endif

            //当前部门小组
            @if (isset($send_department_id) && $send_department_id >0 )
                changeFirstSel(REL_CHANGE.department,SEND_DEPARTMENT_ID,SEND_GROUP_ID, false);

                // 当前的员工
                @if (isset($send_group_id) && $send_group_id >0 )
                    changeFirstSel(REL_CHANGE.staff_department,SEND_GROUP_ID,SEND_STAFF_ID, false);
                @endif
            @endif

        });
	</script>
	<script src="{{ asset('/js/huawu/lanmu/work_edit.js') }}"  type="text/javascript"></script>
@endpush