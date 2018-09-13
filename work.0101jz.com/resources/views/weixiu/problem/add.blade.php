@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线反馈</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
		<table class="table1">
			<tr>
				<th>问题类型</th>
				<td>

					<select class="wnormal" name="work_type_id" onchange="getTwoType()">
						<option  value="">请选择</option>
						@foreach ($arr['typearr'] as  $k=>$v)
						<option  value="{{$v->id}}">{{$v->type_name}}</option>
						@endforeach
					</select>
					<select class="wnormal" name="business_id" >
						<option value="">请选择</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>反馈内容<span class="must">*</span></th>
				<td>
					<textarea type="text" class="inptext wlong" name="content" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
				</td>
			</tr>
		</table>
		<div class="line"></div>
		<table class="table1">
			<tr>
				<th>客户电话</th>
				<td>
					<input type="number" class="inp wnormal" name="call_number" value="" placeholder="来电号码" autofocus  required />
				</td>
			</tr>
		</table>
		<div class="line"></div>
		<table class="table1">
		<tr>
			<th>客户地址</th>
			<td>
				<select class="wmini" name="city_id" onchange="getAreaArr()">
					<option value="">请选择</option>
					@foreach($arr['addarr'] as $k=>$v)
						<option value="{{$v->id}}">{{$v->area_name}}</option>
					@endforeach
				</select>
				<select class="wmini" name="area_id">
					<option value="">请选择</option>
				</select>
				<input type="text" class="inp wnormal" name="address"  />
			</td>
		</tr>

		<tr>
			<th> </th>
			<td><button class="btn btn-l wnormal" id="submitBtn" >提交</button></td>
		</tr>

		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
<script type="text/javascript">
	const TYPE_URL = "{{url('api/weixiu/problem/ajax_gettype')}}";//获取二级分类的url
	const ADDRESS_URL = "{{url('api/weixiu/problem/ajax_getarea')}}";//获取二级地址的url
	const SAVE_URL = "{{url('api/weixiu/problem/ajax_problem_add')}}";//保存地址的url
	const GO_URL = "{{url('weixiu/problem/add')}}";//跳转地址的url
</script>
<script src="{{asset('js/weixiu/lanmu/problem_add.js')}}" type="text/javascript"></script>
@endpush