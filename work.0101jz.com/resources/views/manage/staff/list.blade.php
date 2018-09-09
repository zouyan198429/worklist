@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			<!-- PAGE CONTENT BEGINS -->
			<input type="hidden" value="1" id="page"/><!--当前页号-->
			<input type="hidden" value="10" id="pagesize"/><!--每页显示数量-->
			<input type="hidden" value="-1" id="total"/><!--总记录数量,小于0重新获取-->


			<div class="tabbox" >
				<a href="javascript:void(0);" class="on " onclick="action.add()">添加员工</a>
			</div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<div class="msearch fr">

				<select class="wmini">
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
		<div class="table-header">
			<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出EXCEL</button>
			<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入EXCEL</button>
		</div>
		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>
					<label class="pos-rel">
						<input type="checkbox" class="ace check_all"  value="" onclick="action.seledAll(this)"/>
						<span class="lbl">全选</span>
					</label>
				</th>
				<th>工号</th>
				<th>部门/班组</th>
				<th>姓名</th>
				<th>性别</th>
				<th>职务</th>
				<th>电话</th>
				<th>手机</th>
				<th>QQ</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			{{--
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>113</td>
				<td>话务1组</td>
				<td>张兰兰</td>
				<td>女</td>
				<td>组长</td>
				<td>5854455</td>
				<td>18984684825</td>
				<td>23452345</td>
				<td><a href="{{ url('manage/staff/add') }}" class="btn btn-mini" >修改</a></td>
			</tr>
			--}}
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pagination">
				{{--
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
				--}}
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AJAX_URL = "{{ url('api/manage/staff/ajax_alist') }}";//ajax请求的url
		const ADD_URL = "{{ url('manage/staff/add/0') }}"; //添加url
        const SHOW_URL = "{{url('accounts/info/')}}/";//显示页面地址前缀 + id
        const EDIT_URL = "{{url('manage/staff/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//删除页面地址
		const BATCH_DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('manage/staff/add/0') }}"; //"{{ url('api/manage/staff/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('manage/staff/add/0') }}"; //"{{ url('api/manage/staff/import') }}";//导入EXCEL地址

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
			<td>
				<label class="pos-rel">
					<input  onclick="action.seledSingle(this)" type="checkbox" class="ace check_item" <%if( false &&  !can_modify){%> disabled <%}%>  value="<%=item.id%>"/>
                  <span class="lbl"></span>
                </label>
			</td>
			<td><%=item.work_num%></td>
			<td>话务1组</td>
			<td><%=item.real_name%></td>
			<td><%=item.sex_text%></td>
			<td>组长</td>
			<td><%=item.tel%></td>
			<td><%=item.mobile%></td>
			<td><%=item.qq_number%></td>
			<td>
                <%if( false){%>
                <a href="javascript:void(0);" class="btn btn-mini btn-success"  onclick="action.show(<%=item.id%>)">
                    <i class="ace-icon fa fa-check bigger-60"> 查看</i>
                </a>
                <%}%>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="action.edit(<%=item.id%>)">
                    <i class="ace-icon fa fa-pencil bigger-60"> 编辑</i>
                </a>
                <%if( true){%>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="action.del(<%=item.id%>)">
                    <i class="ace-icon fa fa-trash-o bigger-60"> 删除</i>
                </a>
                <%}%>

			</td>
		</tr>
    <%}%>
</script>
<!-- 列表模板部分 结束-->
<!-- 前端模板结束 -->
{{--<script src="{{ asset('/js/lanmu/account.js') }}"  type="text/javascript"></script>--}}
@endpush