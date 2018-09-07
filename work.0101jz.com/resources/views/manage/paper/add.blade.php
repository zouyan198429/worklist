@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题添加</div>
	<div class="mm">


		<table class="table1">
			<tr>
				<th>试卷名称</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考核人员</th>
				<td>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />全部
					</label>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />话务
					</label>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />维修
					</label>

				</td>
			</tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal" >下一步</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush