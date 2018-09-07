@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题管理</div>

	<div class="mm">
		<div class="mmhead tabbox" id=" ">
			1002455 / 维修业务知识测评
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th>姓名</th>
				<th>成绩</th>
				<th>开考时间</th>
				<th>结束时间</th>
				<th>答题时长</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>柳晓兰</td>
				<td>85</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>74分钟</td>
			</tr>
			<tr>
				<td>柳晓兰</td>
				<td>85</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>74分钟</td>
			</tr>
			<tr>
				<td>柳晓兰</td>
				<td>85</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>74分钟</td>
			</tr>
			<tr>
				<td>柳晓兰</td>
				<td>85</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>74分钟</td>
			</tr>
			<tr>
				<td>柳晓兰</td>
				<td>85</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>74分钟</td>
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