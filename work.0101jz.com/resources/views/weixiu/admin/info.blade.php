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
					345245
				</td>
			</tr>

			<tr>
				<th>姓名</th>
				<td>
					李元元
				</td>
			</tr>
			<tr>
				<th>部门</th>
				<td>
					客服部/接线组
				</td>
			</tr>
			<tr>
				<th>职务</th>
				<td>
					客服
				</td>
			</tr>


			<tr>
				<th>电话</th>
				<td>
					0938-4555744
				</td>
			</tr>
			<tr>
				<th>手机</th>
				<td>
					18955854452
				</td>
			</tr>
			<tr>
				<th>QQ</th>
				<td>
					46554686
				</td>
			</tr>



		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush