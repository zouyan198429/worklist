@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线反馈</div>
	<div class="mm">

		<table class="table1">
			<tr>
				<th>问题类型</th>
				<td>

					<select class="wnormal" name="parent">
						@foreach ($arr as  $k=>$v)
						<option  value="{{$v->id}}">{{$v->type_name}}</option>
						@endforeach
					</select>
					<select class="wnormal" name="twoparent" onclick="getTwoType()">
						<option value="">请选择 </option>
					</select>
				</td>
			</tr>
			<tr>
				<th>反馈内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong"  style=" height:200px" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
				</td>
			</tr>
		</table>
		<div class="line"> </div>
		<table class="table1">
			<tr>
				<th>客户电话</th>
				<td>
					<input type="number" class="inp wnormal" value="" placeholder="来电号码" autofocus  required />
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
				<th> </th>
				<td><button class="btn btn-l wnormal" >提交</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
<script type="text/javascript">
	var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
	const url = "{{url('api/weixiu/problem/ajax_gettype')}}";//获取二级分类的url

</script>
<script src="{{asset('js/weixiu/lanmu/problem_add.js')}}"></script>


@endpush