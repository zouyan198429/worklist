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
				<th>新密码</th>
				<td>
					<input type="text" class="inp wlong" />
				</td>
			</tr>
			<tr>
				<th>确认新密码</th>
				<td>
					<input type="text" class="inp wlong" />
				</td>
			</tr>

			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal" >提交</button></td>
			</tr>




		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush