@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-back"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i></a></div>
			<div class="top-title">考试成绩</div>
		</div>
		<section  id="kaoshi-cj-search" >
			<div class="hd">
				<h1>维修业务知识测评</h1>
				<p>考试时间：2015-02-25</p>
			</div>
			<div class="search">
				<input type="text" value="输入帐号" ><button>搜索</button>
			</div>

			<div class="bd" >
				<h4><i class="fa fa-bookmark fa-fw" aria-hidden="true"></i>查询结果</h4>
				<table class="table05">
					<thead>
					<tr>
						<td>姓名</td>
						<td>分数</td>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th>张法吉</th>
						<td>85分</td>
					</tr>


					</tbody>
				</table>

			</div>

	</div>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush
