@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-title">问题反馈</div>
		</div>
		<div id="prlblem" class="table4" >
			<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<dl class="inp">
				<dt>问题类型</dt>
				<dd>
					<select class="wnormal" name="work_type_id" onchange="getTwoType()">
						<option  value="">请选择</option>
						@foreach ($arr['typearr'] as  $k=>$v)
							<option  value="{{$v->id}}">{{$v->type_name}}</option>
						@endforeach
					</select>
					<select class="wnormal" name="business_id" >
						<option value="">请选择</option>
					</select>
				</dd>
			</dl>
			<dl class="inp">
				<!-- 					<dt>反馈内容<span class="must">*</span></dt>
                 -->					<dd>
					<textarea type="text" class="inptext wlong" name="content"  style=" height:200px"  placeholder="反馈内容" /></textarea>
				</dd>
			</dl>
			<div class="k10"></div>
			<div class="line"></div>
			<div class="k10"></div>
			<dl class="inp">
				<!-- 					<dt>客户电话</dt>
                 -->					<dd>
					<input type="text" class="inp wlong" value="" name="call_number" placeholder="客户电话" autofocus  required />
				</dd>
			</dl>
			<dl class="inp">
				<dt>客户地址</dt>
				<dd>
					<select class="wnormal" name="city_id" onchange="getAreaArr()">
						<option value="">请选择</option>
						@foreach($arr['addarr'] as $k=>$v)
							<option value="{{$v->id}}">{{$v->area_name}}</option>
						@endforeach
					</select>
					<select class="wnormal"  name="area_id">
						<option value="">请选择</option>
					</select>
					<div class="k10"></div>
					<input type="text" class="inp wlong" name="address"  placeholder="详细地址"  />
				</dd>
			</dl>
			<dl>
				<dt> </dt>
				<div class="k10"></div>
				<dd><button class="btn btn-l wlong"  id="submitBtn"  >提交</button>
				</dd>
			</dl>
</form>
		</div>

		@include('mobile.layout_public.menu', ['menu_id' => 4])


	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	{{--<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>--}}
	<script src="{{asset('staticmobile/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>

<script type="text/javascript">
	const TYPE_URL = "{{url('api/m/problem/ajax_gettype')}}";//获取二级分类的url
	const ADDRESS_URL = "{{url('api/m/problem/ajax_getarea')}}";//获取二级地址的url
	const SAVE_URL = "{{url('api/m/problem/ajax_problem_add')}}";//保存地址的url
	const GO_URL = "{{url('m/problem/add')}}";//跳转地址的url
</script>
<script src="{{asset('js/m/lanmu/problem_add.js')}}" type="text/javascript"></script>

@endpush
