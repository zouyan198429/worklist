@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 管理员管理</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="know_add.html" class="on" >添加管理员</a>
			</div>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th>用户名</th>
				<th>真实姓名</th>
				<th>职位/角色</th>
				<th width=200 >操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>admin</td>
				<td>刘达轩</td>
				<td>超级管理员</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a>  </td>
			</tr>
			<tr>
				<td>guanli</td>
				<td>李三级</td>
				<td>管理员</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a>  </td>
			</tr>
			</tbody>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush