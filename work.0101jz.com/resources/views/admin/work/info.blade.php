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
				<th>来源/号码/联系电话</th>
				<td>
					{{ $caller_type_name or '' }}/
					{{ $call_number or '' }}/
					{{ $contact_number or '' }}
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
			<tr style="display: none;" >
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
				<th>到期时间</th>
				<td>
					{{ $expiry_time or '' }}
				</td>
			</tr>
		</table>
		<div class="line" style="display: none;"> </div>
		<table class="table1" style="display: none;">
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
				<th>派发记录</th>
				<td>
					<table class="table1">
						<tr>
							<th>操作人</th>
							<th>派发部门/小组</th>
							<th>派发员工</th>
							<th>操作时间</th>
						</tr>
						@foreach ($sendLogs as $log)
						<tr>
							<td>{{ $log['operate_staff_name'] or '' }}</td>
							<td>{{ $log['send_department_name'] or '' }}/{{ $log['send_group_name'] or '' }}</td>
							<td>{{ $log['send_staff_name'] or '' }}</td>
							<td>{{ $log['created_at'] or '' }}</td>
						</tr>
						@endforeach

					</table>

				</td>
			</tr>
			<tr>
				<th>日志记录</th>
				<td>
					<table class="table1">
						<tr>
							<th>操作人</th>
							<th>操作内容</th>
							<th>操作时间</th>
						</tr>
						@foreach ($logs as $log)
							<tr>
								<td>{{ $log['real_name'] or '' }}</td>
								<td>{!! $log['content'] or '' !!}</td>
								<td>{{ $log['created_at'] or '' }}</td>
							</tr>
						@endforeach

					</table>

				</td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush