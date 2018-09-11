@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-back"><a href="javascript:history.go(-1)">返回</a></div>
			<div class="top-title">我的帐号</div>
		</div>
		<section class="main" >
			<div class="dlbox">


				<dl>
					<dt>电话</dt>
					<dd>
						0938-4555744
					</dd>
				</dl>
				<dl>
					<dt>手机</dt>
					<dd>
						18955854452
					</dd>
				</dl>
				<dl>
					<dt>QQ</dt>
					<dd>
						46554686
					</dd>
				</dl>



			</div>
		</section>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush