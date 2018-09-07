@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试卷列表</div>

	<div class="mm">
		<div class="mmhead tabbox" id=" ">
			<a href="{{ url('admin/paper/add') }}" class="on">生成试卷</a> <p>1. 设定试卷标题，2.选择分类 ，4.选择试题 3. 生成试卷</p>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th></th>
				<th>试卷名称</th>
				<th>试题总数</th>
				<th>目标岗位</th>
				<th>生成日期</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>维修业务知识测评</td>
				<td>100</td>
				<td>维修</td>
				<td>2018-05-21</td>
				<td><a href="{{ url('admin/exam/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>维修业务知识测评</td>
				<td>100</td>
				<td>维修</td>
				<td>2018-05-21</td>
				<td><a href="{{ url('admin/exam/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>维修业务知识测评</td>
				<td>100</td>
				<td>维修</td>
				<td>2018-05-21</td>
				<td><a href="{{ url('admin/exam/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>维修业务知识测评</td>
				<td>100</td>
				<td>维修</td>
				<td>2018-05-21</td>
				<td><a href="{{ url('admin/exam/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>维修业务知识测评</td>
				<td>100</td>
				<td>维修</td>
				<td>2018-05-21</td>
				<td><a href="{{ url('admin/exam/add') }}" class="btn" >修改</a></td>
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