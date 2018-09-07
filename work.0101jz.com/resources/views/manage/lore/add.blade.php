@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线学习</div>
	<div class="mm">


		<table class="table1">
			<tr>
				<th>知识分类</th>
				<td>
					<select class="wnormal">
						<option value="a01">营销知识</option>
						<option value="a02">维修知识</option>
						<option value="a03">公司资料</option>
						<option value="a04">其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>标题</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong"  style=" height:500px" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
				</td>
			</tr>
			<tr>
				<th>推荐级别</th>
				<td>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />★
					</label>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />★★
					</label>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />★★★
					</label>
				</td>
			<tr>
			<tr>
				<th>适用岗位</th>
				<td>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />全部
					</label>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />话务
					</label>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />维护
					</label>
					<label>
						<input type="checkbox" name="checkbox" value="checkbox" />行政
					</label>
				</td>
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