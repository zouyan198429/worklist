@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的客户</div>
	<div class="mm">


		<table class="table1">

			<tr>
				<th>来源/号码<span class="must">*</span></th>
				<td>
					<select class="wmini">
						<option value="a01">客户来电</option>
						<option value="a02">销售采集</option>
						<option value="a03">代理商反馈</option>
						<option value="a04">其他</option>
					</select>
					<input type="number" class="inp wnormal" value="" placeholder="来电号码" autofocus  required />
				</td>
			</tr>

			<tr>
				<th>维修类型</th>
				<td>

					<select class="wnormal">
						<option value="a01">固定电话</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
					<select class="wnormal">
						<option value="a01">新装</option>
						<option value="a02">断网</option>
						<option value="a03">迁移</option>
						<option value="a04">其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>工单内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
				</td>
			</tr>
			<tr >
				<th> </th>
				<td>
					<a class="tags">标签1 </a> <a class="tags">标签1 </a> <a class="tags">标签1 </a> <a class="tags">标签1 </a>
				</td>
			</tr>

			<tr >
				<th></th>
				<td>
					<label><input type="radio" name="jinji" value="1" checked="checked" />2小时 </label>
					<label><input type="radio" name="jinji" value="2">4小时 </label>
					<label><input type="radio" name="jinji" value="3">8小时 </label>
					<label><input type="radio" name="jinji" value="3">12小时 </label>
					<label><input type="radio" name="jinji" value="3">24小时 </label>
					<label><input type="radio" name="jinji" value="3">48小时 </label>
					<label><input type="radio" name="jinji" value="3">72小时 </label>
				</td>
			</tr>
			<tr>
				<th>预约处理时间</th>
				<td>
					<input type="text" id="yuyuetime" class="inp wlong" />
					<datalist id="yuyuetime" style="display:none" >
						<option value="今天">今天</option>
						<option value="明天">明天</option>
						<option value="后天">后天</option>
					</datalist>
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">
			<tr>
				<th>客户姓名</th>
				<td>
					<input type="text" class="inp wlong" />
				</td>
			</tr>
			<tr>
				<th>客户性别</th>
				<td>
					<label><input type="radio" name="sex" value="2"  checked="checked" />男 </label>
					<label><input type="radio" name="sex" value="3"  >女 </label>
				</td>
			</tr>
			<tr>
				<th>客户类别</th>
				<td>
					<label><input type="radio" name="kehu"  value="1" checked>未知 </label>
					<label><input type="radio" name="kehu"  value="2">个人 </label>
					<label><input type="radio" name="kehu"  value="3">企业 </label>
					<label><input type="radio" name="kehu"  value="4">政府 </label>
					<label><input type="radio" name="kehu"  value="5">学校 </label>
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">

			<tr>
				<th>客户地址</th>
				<td>
					<select class="wmini">
						<option value="a01">区</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
					<select class="wmini">
						<option value="a01">街道</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
					<input type="text" class="inp wnormal" />
				</td>
			</tr>
			<tr>
				<th>派发到</th>
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
					<p class="tip">客户所在区街道和责任员工相对应</p>
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