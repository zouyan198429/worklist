@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题添加</div>
	<div class="mm">


		<table class="table1">
			<tr>
				<th>分类</th>
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
				<th>题目</th>
				<td>
					<textarea type="text" class="inptext wlong"  style=" height:100px" /></textarea>
				</td>
			</tr>

			<tr>
				<th>答案</th>
				<td>
					<label>
						A <input type="text" class="inp wlong"  /><br />
					</label>
					<label>
						B <input type="text" class="inp wlong" /><br />
					</label>
					<label>
						C <input type="text" class="inp wlong" /><br />
					</label>
					<label>
						D <input type="text" class="inp wlong" />
					</label>

				</td>
			</tr>
			<tr>
				<th>类型</th>
				<td>
					<label>
						<input type="radio" name="ptype" value="ptype" checked="checked"  />单选
					</label>
					<label>
						<input type="radio" name="ptype" value="ptype" />多选
					</label>
				</td>
			</tr>
			<tr>
				<th>正确答案</th>
				<td>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />A
					</label>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />B
					</label>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />C
					</label>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" />D
					</label>
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