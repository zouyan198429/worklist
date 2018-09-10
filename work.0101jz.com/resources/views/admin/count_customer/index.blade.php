@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 来电统计</div>
	<div class="mm">
		<div class="tubiao"  >

			<p>客户组成表(企业、个人、...)</p>

			<img src="{{asset('staticadmin/images/tb2.png')}}" />

			<p>客户区域分布表(区域)</p>
			<img src="{{asset('staticadmin/images/tb1.png')}}" />


			<p>工单类型表</p>

			<img src="{{asset('staticadmin/images/tb3.png')}}" />

		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush