@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈问题</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<div class="tabbox" >
			</div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">

					<select class="wmini" name="work_type_id" >
						<option value="">全部</option>
						@foreach ($problem_type_kv as $k=>$txt)
							<option value="{{ $k }}"  >{{ $txt }}</option>
						@endforeach
					</select>
					<input type="text" value=""  name="keyWord"  placeholder="客户电话"/>
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>
		</div>
		<table  id="dynamic-table" class="table2">
			<thead>
			<tr>
				{{--<th></th>--}}
				<th>类型</th>
				<th>内容</th>
				{{--<th>回复</th>--}}
				<th>客户电话</th>
				<th>地址</th>
				<th>发送人</th>
				<th>时间</th>
				{{--<th>操作</th>--}}
			</tr>
			</thead>
			<tbody id="data_list">
	{{--		<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>固定电话/新装</td>
				<td>一个问题的内容</td>
				<td>张兰兰</td>
				<td>15699888555</td>
				<td>雷小明</td>
				<td>18955263568</td>
				<td>2018-05-25 14:22</td>
				<td><a href="m_problem_hf.html" class="btn btn-mini" >回复</a></td>
			</tr>--}}
			</tbody>
		</table>

		<div class="mmfoot" >
			<div class="mmfleft"></div>
			<div class="pagination"></div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')

<script type="text/javascript">
	var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
	const AJAX_URL = "{{ url('api/admin/problem/ajax_alist') }}";//ajax请求的url
	const ADD_URL = ""; // {{ url('admin/problem/add/0') }} //添加url
	const SHOW_URL = "";//{{url('admin/problem/info/')}}/ //显示页面地址前缀 + id
    const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
	const EDIT_URL = "";//{{url('admin/problem/add/')}}/  //修改页面地址前缀 + id
	const DEL_URL = "";  //{{ url('api/admin/problem/ajax_del') }}  //删除页面地址
	const BATCH_DEL_URL = ""; //{{ url('api/admin/problem/ajax_del') }}  //批量删除页面地址
	const EXPORT_EXCEL_URL = ""; //{{ url('admin/problem/add/0') }}  "{{ url('api/admin/problem/export') }}";//导出EXCEL地址
	const IMPORT_EXCEL_URL = ""; //{{ url('admin/problem/add/0') }}"{{ url('api/admin/problem/import') }}";//导入EXCEL地址

	const REPLY_URL = "{{ url('admin/problem/reply/')}}/";// 回复地址
    const REPLY_TITLE = "回复";
</script>
<script src="{{asset('js/common/list.js')}}"></script>
<script src="{{ asset('/js/admin/lanmu/problem.js') }}"  type="text/javascript"></script>
@endpush