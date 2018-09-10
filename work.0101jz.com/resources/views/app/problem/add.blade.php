@extends('layouts.app')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="prlblem" >
		<table class="table4">
			<tr>
				<th>问题类型</th>
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
				<th>反馈内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong"  style=" height:200px"  placeholder="反馈内容" /></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div id="prlblem" >

		<table class="table4">
			<tr>
				<th>客户电话</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="客户电话" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>客户地址</th>
				<td>
					<select class="wnormal">
						<option value="a01">区</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
					<select class="wnormal">
						<option value="a01">街道</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th> </th>
				<td>
					<input type="text" class="inp wlong" placeholder="详细地址"  />
				</td>
			</tr>

			<tr>
				<th> </th>
				<td><button class="btn btn-l wlong" >提交</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script src="{{asset('staticapp/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>
@endpush