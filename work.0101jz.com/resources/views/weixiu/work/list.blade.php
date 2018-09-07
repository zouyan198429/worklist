@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的工单</div>
	<div class="mm">


		<div class="mmhead" id="mywork">
			<div class="tabbox" ><a href="#" class="on">全部工单</a> </div>
			<div class="msearch fr">
				<select style="width:80px; height:28px;">
					<option value="a01">手机号</option>
					<option value="a02">姓名</option>
					<option value="a03">工单号</option>
				</select> <input type="text" value=""  /> <button class="btn btn-normal">搜索</button> </div>
		</div>


		<table class="table2">
			<thead>
			<tr>
				<th>工单号</th>
				<th>下单时间</th>
				<th>工单等级</th>
				<th>响应时间</th>
				<th>来电号码</th>
				<th>客户姓名</th>
				<th>客户类别</th>
				<th>客户位置</th>
				<th>来电次数</th>
				<th>上次到访时间</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td>剩余1小时12分</td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn" >结单</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td><span class="red" >超时</a></td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn" >结单</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td> </td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn btn-gray" >查看</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td> </td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn btn-gray" >查看</a></td>
			</tr>

			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pages">
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush