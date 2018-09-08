@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 角色/权限管理</div>
	<div class="mm">

		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="know_add.html" class="on" >添加角色</a>
			</div>
		</div>

		<table class="table2">
			<thead>
			<tr>
				<th width=200>角色</th>
				<th>权限</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>超级管理员</td>
				<td> </td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			<tr>
				<td>管理员</td>
				<td> </td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			<tr>
				<td>维修部经理</td>
				<td> </td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			<tr>
				<td>话务经理</td>
				<td> </td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a> </td>
			</tr>
			</tbody>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush