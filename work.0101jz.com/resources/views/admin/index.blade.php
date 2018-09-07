@extends('layouts.admin')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 首页</div>
	<div class="mm">

		<h2>信息总揽</h2>
		<div class="row" >
			<div class="col-sm">
				<p>0</p>
				<h4>今日受理工单</h4>
			</div>
			<div class="col-sm">
				<p>5</p>
				<h4>紧急工单</h4>
			</div>
			<div class="col-sm">
				<p>0</p>
				<h4>昨日遗留工单</h4>
			</div>
			<div class="col-sm">
				<p>0</p>
				<h4>完成工单</h4>
			</div>

		</div>


	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush