@extends('layouts.app')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="customer" >
		<table class="table3">
			<thead>
			<tr>
				<th>来电号码</th>
				<th>客户姓名</th>
				<th>客户类别</th>
				<th>客户位置</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
			</tr>
			<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
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
	<script src="{{asset('app/js/sidebar-menu.js')}}"></script>
	<script>
        $.sidebarMenu($('.sidebar-menu'))
	</script>
@endpush
