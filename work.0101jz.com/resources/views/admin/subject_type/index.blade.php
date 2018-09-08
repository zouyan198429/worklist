@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题分类</div>
	<div class="mm">

		<table class="table2">
			<thead>
			<tr>
				<th>分类</th>
				<th>显示顺序 </th>
				<th width=200 >操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>电话销售</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a>  </td>
			</tr>
			<tr>
				<td>电话销售</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a>  </td>
			</tr>
			<tr>
				<td>电话销售</td>
				<td>10</td>
				<td><a href="" class="btn" >修改</a>  <a href=""  class="btn btn-red">删除</a>  </td>
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