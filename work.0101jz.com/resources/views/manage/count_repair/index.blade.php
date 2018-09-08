@extends('layouts.manage')

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
		<div class="tubiao" >
			<img src="{{asset('manage/images/tb1.png')}}" />

			<p>2018-05-01--- 2018-05-28(今天)</p>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th>日期</th>
				<th>维修数量</th>
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
		<table class="table2">
			<thead>
			<tr>
				<th>月份</th>
				<th>维修数量</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>2015-04</td>
				<td>2133</td>
			</tr>
			<tr>
				<td>2015-03</td>
				<td>2633</td>
			</tr>
			<tr>
				<td>2015-02</td>
				<td>2363</td>
			</tr>
			<tr>
				<td>2015-01</td>
				<td>2343</td>
			</tr>
			</tbody>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush