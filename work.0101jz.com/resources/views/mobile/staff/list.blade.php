@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-back"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i></a></div>
			<div class="top-title">我的同事</div>
		</div>
		<section class="main" id="tongshi" >
			@foreach ($department_list as $department)
			<div class="hd">
				<h4><i class="fa fa-bars  fa-fw" aria-hidden="true"></i> {{ $department['department_name'] or '' }}</h4>
			</div>
			<ul class="listtext3">
				@foreach ($department['staff'] as $staff)
				<li>
					<div class="name" >
						<i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i>  {{ $staff['real_name'] or '' }}
					</div>
					<div class="tell"><a href="tel:{{ $staff['mobile'] or '' }}" class="btn" ><i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> {{ $staff['mobile'] or '' }}  </a></div>
					<div class="c"></div>
				</li>
				@endforeach

			</ul>
			@endforeach
	</div>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush