@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题添加</div>
	<div class="mm">


		<table class="table1">
			<tr>
				<th>考试名称</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>开考日期</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>开始时间</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>结束时间</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />

				</td>
			</tr>
			<tr>
				<th>参与人员</th>
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
				<th>选择试卷</th>
				<td>
					<a href="" class="btn btn-gray" >试卷列表..</a>
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