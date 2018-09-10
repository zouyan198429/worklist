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

					<select name="filename" class="wmini">
						<option value="a01">全部</option>
						<option value="a02">维修部</option>
						<option value="a03">话务部</option>
						<option value="a04">行政部</option>
					</select>
					<input type="text" value=""  name="keyword" />
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
				<th>客户电话</th>
				<th>地址</th>
				<th>发送人</th>
				<th>手机</th>
				<th>时间</th>
				<th>操作</th>
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
			</tr>
			<tr>
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

		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pagination"></div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')

<script type="text/javascript">
	var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
	const AJAX_URL = "{{ url('api/admin/problem/ajax_alist') }}";//ajax请求的url
	const ADD_URL = ""; // {{ url('manage/staff/add/0') }} //添加url
	const SHOW_URL = "";//{{url('accounts/info/')}}/ //显示页面地址前缀 + id
	const EDIT_URL = "";//{{url('manage/staff/add/')}}/  //修改页面地址前缀 + id
	const DEL_URL = "";  //{{ url('api/manage/problem/ajax_del') }}  //删除页面地址
	const BATCH_DEL_URL = ""; //{{ url('api/manage/staff/ajax_del') }}  //批量删除页面地址
	const EXPORT_EXCEL_URL = ""; //{{ url('manage/staff/add/0') }}  "{{ url('api/manage/staff/export') }}";//导出EXCEL地址
	const IMPORT_EXCEL_URL = ""; //{{ url('manage/staff/add/0') }}"{{ url('api/manage/staff/import') }}";//导入EXCEL地址

</script>
<script src="{{asset('js/common/list.js')}}"></script>

<!-- 前端模板部分 -->
<!-- 列表模板部分 开始  <!-- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
<script type="text/template"  id="baidu_template_data_list">

	<%for(var i = 0; i<data_list.length;i++){
	var item = data_list[i];
	{{--var account_status = item.account_status; --}}
	var can_modify = false;
	if( item.account_issuper==0 ){ //&& (item.supplier_status & (1+8))>0
	can_modify = true;
	}
	%>

	<tr>
		{{--<td>--}}
		{{--<label class="pos-rel">--}}
		{{--<input  onclick="action.seledSingle(this)" type="checkbox" class="ace check_item" <%if( false &&  !can_modify){%> disabled <%}%>  value="<%=item.id%>"/>--}}
		{{--<span class="lbl"></span>--}}
		{{--</label>--}}
		{{--</td>--}}
		<td><%=item.type_name%></td>
		<td><%=item.content%></td>
		<td><%=item.call_number%></td>
		<td><%=item.city_name%><%=item.area_name%><%=item.address%></td>
		<td><%=item.customer_name%></td>
		<td><%=item.call_number%></td>
		<td><%=item.created_at%></td>
		<td><a href="{{url('manage/problem/return_send')}}/<%=item.id%>" class="btn btn-mini" >回复</a></td>
	</tr> <%}%>
</script>
<!-- 列表模板部分 结束-->
<!-- 前端模板结束 -->
@endpush