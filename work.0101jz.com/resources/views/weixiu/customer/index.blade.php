@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的客户</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<div class="tabbox" ><a href="#" class="on">全部客户</a>  <a href="#">企业客户</a>  <a href="#">多次来电客户</a> </div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">
					<select class="wmini" name="file">
						<option value="">全部</option>
						<option value="customer_name">客户姓名</option>
						<option value="call_number">来电电话</option>
					</select>
					<input type="text" value=""  name="keyword" />
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>


		<table  id="dynamic-table" class="table2">
			<thead>
			<tr>
				<th>来电号码</th>
				<th>客户姓名</th>
				<th>客户类别</th>
				<th>客户位置</th>
				<th>来电次数</th>
				<th>上次到访时间</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody id="data_list">
			{{--<tr>
				<td>15366658554</td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn" >标记</a></td>
			</tr>--}}
			</tbody>
		</table>
			<div class="mmfoot">
				<div class="mmfleft"></div>
				<div class="pagination"></div>
			</div>

	</div>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
<script type="text/javascript">
	var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
    const AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
	const AJAX_URL = "{{ url('api/weixiu/customer/ajax_alist') }}";//ajax请求的url
	const ADD_URL = ""; // {{ url('weixiu/customer/add/0') }} //添加url
	const SHOW_URL = "";//{{url('weixiu/customer/info/')}}/ //显示页面地址前缀 + id
    const SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
	const EDIT_URL = "";//{{url('weixiu/customer/add/')}}/  //修改页面地址前缀 + id
	const DEL_URL = "";  //{{ url('api/weixiu/customer/ajax_del') }}  //删除页面地址
	const BATCH_DEL_URL = ""; //{{ url('api/weixiu/customer/ajax_del') }}  //批量删除页面地址
	const EXPORT_EXCEL_URL = ""; //{{ url('weixiu/customer/add/0') }}  "{{ url('api/weixiu/customer/export') }}";//导出EXCEL地址
	const IMPORT_EXCEL_URL = ""; //{{ url('weixiu/customer/add/0') }}"{{ url('api/weixiu/customer/import') }}";//导入EXCEL地址

</script>
<script src="{{asset('js/common/list.js')}}"></script>

<!-- 前端模板部分 -->
<!-- 列表模板部分 开始  <!-- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
<script type="text/template"  id="baidu_template_data_list">

	<%for(var i = 0; i<data_list.length;i++){
	var item = data_list[i];
	{{--var account_status = item.account_status; --}}
	var can_modify = false;
	{{--//&& (item.supplier_status & (1+8))>0--}}
	if( item.issuper==0 ){
	can_modify = true;
	}
	%>

	<tr>
		<td><%=item.call_number%></td>
        <td><%=item.customer_name%>
        <td><%=item.type_name%></td>
        <td><%=item.address%></td>
        <td><%=item.call_num%></td>
        <td><%=item.last_call_date%></td>
  		<td><a href="{{url('api/weixiu/customer/ajax_biaoji')}}" class="btn" >标记</a></td>
    </tr>
    <%}%>
</script>
<!-- 列表模板部分 结束-->
<!-- 前端模板结束 -->
@endpush
