@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题管理</div>

	<div class="mm">
		<div class="mmhead" id=" ">
			<div class="tabbox fl" style=" width:400px; float:left;" ><a href="{{ url('manage/subject/add') }}" class="on">添加试题</a> <a href="#" >导入试题</a> </div>
			<div class="msearch fr" style=" width:400px; float:right;" >
				<select style="width:110px; height:28px;">
					<option value="a01">销售技巧</option>
					<option value="a02">销售技巧</option>
					<option value="a03">销售技巧</option>
				</select>
				<input type="text" value=""  />
				<button class="btn btn-normal">搜索</button>
			</div>
		</div>

		<table class="table2">
			<thead>
			<tr>
				<th></th>
				<th>题目</th>
				<th>分类</th>
				<th>类型</th>
				<th>添加时间</th>
				<th>添加人</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>您被分到一个单位当领导，想提出一些解决工...</td>
				<td>销售技巧</td>
				<td>单选</td>
				<td>2018-05-21</td>
				<td>张栋</td>
				<td><a href="{{ url('manage/subject/add') }}" class="btn" >修改</a></td>
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