@extends('layouts.huawualert')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 工单详情</div>--}}
	<div class="mm">
		<table class="table1">

			<tr>
				<th>工单号</th>
				<td>
					{{ $work_num or '' }}
				</td>
			</tr>
			<tr>
				<th>来源/号码</th>
				<td>
					{{ $caller_type_name or '' }}/
					{{ $call_number or '' }}
				</td>
			</tr>

			<tr>
				<th>维修类型</th>
				<td>
					{{ $type_name or '' }}/
					{{ $business_name or '' }}
				</td>
			</tr>
			<tr>
				<th>工单内容</th>
				<td>{!! $content or '' !!}
				</td>
			</tr>
			<tr >
				<th> </th>
				<td>
					@foreach ($serviceTagList as $k=>$txt)
						<a class="tags"><label><input type="radio" disabled="disabled" name="tag_id" value="{{ $k }}"  @if(isset($tag_id) && $tag_id == $k) checked="checked"  @endif>{{ $txt }} </label></a>
					@endforeach

				</td>
			</tr>

			<tr >
				<th></th>
				<td>
					@foreach ($serviceTimeList as $k=>$txt)
						<label><input type="radio"  disabled="disabled"  name="time_id"  value="{{ $k }}"  @if(isset($time_id) && $time_id == $k) checked="checked"  @endif />{{ $txt }} </label>
					@endforeach
				</td>
			</tr>
			<tr>
				<th>预约处理时间</th>
				<td>
					{{ $book_time or '' }}
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">
			<tr>
				<th>客户姓名</th>
				<td>
					{{ $customer_name or '' }}
				</td>
			</tr>
			<tr>
				<th>客户性别</th>
				<td>
					<label><input  disabled="disabled" type="radio" name="sex" value="1" @if (isset($sex) && $sex == 1 ) checked @endif>男</label>&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input  disabled="disabled" type="radio" name="sex" value="2" @if (isset($sex) && $sex == 2 ) checked @endif>女</label>
				</td>
			</tr>
			<tr>
				<th>客户类别</th>
				<td>
					@foreach ($customerTypeList as $k=>$txt)
						<label><input type="radio"   disabled="disabled" name="type_id"  value="{{ $k }}"  @if(isset($type_id) && $type_id == $k) checked="checked"  @endif />{{ $txt }} </label>
					@endforeach
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">

			<tr>
				<th>客户地址</th>
				<td>
					{{ $city_name or '' }}
					{{ $area_name or '' }}
					{{ $address or '' }}
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

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush