@extends('layouts.admin')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 工单处理</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				@foreach ($count_types as $k=>$txt)
					<a href="javascript:void(0)"   data-count_type="{{ $k }}" class="count_types_click @if ($k == $defaultCountType) on @endif ">{{ $txt }}</a>
				@endforeach
			</div>

			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">
					<select style="width:80px; height:28px; display: none;" name="count_type" >
						<option value="">全部</option>
						@foreach ($count_types as $k=>$txt)
							<option value="{{ $k }}"   @if ($k == $defaultCountType) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<input type="text" id="yuyuetime" name="begin_date" class="begin_date" value="{{ $begin_date or '' }}"  placeholder="开始日期" style="width:100px;" />
					--
					<input type="text" id="yuyuetime" name="end_date" class="end_date" value="{{ $end_date or '' }}"  placeholder="结束日期" style="width:100px;" />
					<button class="btn btn-normal  search_frm ">搜索</button>
				</div>
			</form>
		</div>
		<div id="containerParent"></div>
		<p class="chart_title">{{--2018-05-01--- 2018-05-28(今天)--}}</p>
		<table class="table2">
			<thead>
			<tr>
				<th>日期</th>
				<th>来电数量</th>
			</tr>
			</thead>
			<tbody id="dataList">
			{{--
			<tr>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			--}}
			</tbody>
		</table>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	{{--这个js是引用echarts的插件的js--}}
	{{--<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>--}}
	<script type="text/javascript" src="{{asset('js/common/echarts.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/common/graph.js')}}"></script>
	<script type="text/javascript">
        var WORK_COUNT_URL = "{{ url('api/admin/work/ajax_repair_count') }}";// ajax工单统计 url
        var BAR_GRAPH_ID = "container";// 柱状图id
        var BEGIN_DATE = "{{ $begin_date or '' }}" ;//开始日期
        var END_DATE = "{{ $end_date or '' }}" ;//结束日期

	</script>
	<script src="{{ asset('js/admin/lanmu/count_repair.js') }}"  type="text/javascript"></script>
@endpush