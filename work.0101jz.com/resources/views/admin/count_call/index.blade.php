@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 来电统计</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="#">按天统计</a> <a href="" >每月统计</a>
			</div>
		</div>
		<div class="tubiao" id="container" style="height: 100%;height: 500px"></div>
		<p>2018-05-01--- 2018-05-28(今天)</p>
		<table class="table2">
			<thead>
			<tr>
				<th>日期</th>
				<th>来电数量</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			</tbody>
		</table>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
{{--这个js是引用echarts的插件的js--}}
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script src="{{asset('js/adminpublic/graph.js')}}"></script>
<script type="text/javascript">
	var dataAxis = ['点', '击', '柱', '子', '或', '者', '两', '指', '在', '触', '屏', '上', '滑', '动', '能', '够', '自', '动', '缩', '放'];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var id = "container";
	/*
	* ymax: 柱状图阴影部分的高度(如果数值高于左边的数字，左边的数字也会增长)
	* dataAxis：柱状图上的显示的文字(一维数组)
	* data：柱状图上的数值(一维数组)
	* id：div的id名
	* */
	zhuzhuangtu(yMax,data,dataAxis,id);
</script>
@endpush