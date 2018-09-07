@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 知识分类</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="know_add.html" class="on" >添加分类</a>
			</div>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th>分类</th>
				<th>排序</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>营销话术</td>
				<td>21</td>
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