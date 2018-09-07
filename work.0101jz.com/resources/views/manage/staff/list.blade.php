@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			<div class="tabbox" >
				<a href="{{ url('manage/staff/add') }}" class="on">添加员工</a>
			</div>
			<div class="msearch fr">

				<select class="wmini">
					<option value="a01">全部</option>
					<option value="a02">维修部</option>
					<option value="a03">话务部</option>
					<option value="a04">行政部</option>
				</select>
				<input type="text" value=""  />
				<button class="btn btn-normal">搜索</button>
			</div>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th></th>
				<th>工号</th>
				<th>部门/班组</th>
				<th>姓名</th>
				<th>性别</th>
				<th>职务</th>
				<th>电话</th>
				<th>手机</th>
				<th>QQ</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
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