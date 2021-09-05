@extends('layouts.huawualert')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 工单详情</div>--}}
	<div class="mm">
		@include('huawu.work.publicinfo')
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush