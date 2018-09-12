@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 个人信息</div>
	<div class="mm">


		<table class="table1">

			<tr>
				<th>工号</th>
				<td>
					{{ $work_num or '' }}
				</td>
			</tr>

			<tr>
				<th>姓名</th>
				<td>
					{{ $real_name or '' }}
				</td>
			</tr>
			<tr>
				<th>部门</th>
				<td>

					{{ $department_name or '' }}/
					{{ $group_name or '' }}
				</td>
			</tr>
			<tr>
				<th>职务</th>
				<td>

					{{ $position_name or '' }}
				</td>
			</tr>


			<tr>
				<th>电话</th>
				<td>

					{{ $tel or '' }}
				</td>
			</tr>
			<tr>
				<th>手机</th>
				<td>

					{{ $mobile or '' }}
				</td>
			</tr>
			<tr>
				<th>QQ</th>
				<td>

					{{ $qq_number or '' }}
				</td>
			</tr>



		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush