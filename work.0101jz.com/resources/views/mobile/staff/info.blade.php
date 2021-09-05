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
					<dt>工号</dt>
					<dd>
						{{ $work_num or '' }}
					</dd>
				</dl>
				<dl>
					<dt>姓名</dt>
					<dd>
						{{ $real_name or '' }}
					</dd>
				</dl>
				<dl>
					<dt>部门</dt>
					<dd>
						{{ $department_name or '' }}/
						{{ $group_name or '' }}
					</dd>
				</dl>
				<dl>
					<dt>职务</dt>
					<dd>
						{{ $position_name or '' }}
					</dd>
				</dl>
				<dl>
					<dt>电话</dt>
					<dd>
						{{ $tel or '' }}
					</dd>
				</dl>
				<dl>
					<dt>手机</dt>
					<dd>
						{{ $mobile or '' }}
					</dd>
				</dl>
				<dl>
					<dt>QQ</dt>
					<dd>
						{{ $qq_number or '' }}
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