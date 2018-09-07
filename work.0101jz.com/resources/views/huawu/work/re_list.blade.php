@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 首页</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" ><a href="#" class="on">历史工单</a>  <a href="#">待反馈工单</a>  <a href="#">需回访工单</a>  <a href="#">已完成工单</a></div>
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
				<th></th>
				<th>工单编号</th>
				<th>录入时间</th>
				<th>来电号码</th>
				<th>优先级</th>
				<th>客户姓名</th>
				<th>客户类别</th>
				<th>客户位置</th>
				<th>派发到</th>
				<th>是否反馈</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>2018-05-25</td>
				<td>15366658554</td>
				<td>一般</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>雷明</td>
				<td>暂无反馈</td>
				<td><a href="" class="btn" >记录</a> <a href="" class="btn">重新指派</a></td>
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