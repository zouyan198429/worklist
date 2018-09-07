@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加员工</div>
	<div class="mm">

		<table class="table1">
			<tr>
				<th>工号<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>部门/班组</th>
				<td>

					<select class="wnormal">
						<option value="a01">部门</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
					<select class="wnormal">
						<option value="a01">员工</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>姓名<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>性别</th>
				<td>
					<input type="text" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>职务</th>
				<td>
					<input type="text" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>座机电话</th>
				<td>
					<input type="number" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>手机</th>
				<td>
					<input type="number" class="inp wnormal" value="" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>QQ</th>
				<td>
					<input type="number" class="inp wnormal" value="" placeholder=" " autofocus  required />
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