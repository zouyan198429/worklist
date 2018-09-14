@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的工单</div>
	<div class="mm">
		@include('common.pageParams')
		<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);"  data-status="" class="on status_click">全部工单</a>
				@foreach ($status as $k=>$txt)
					<a href="javascript:void(0)" data-status="{{ $k }}" class="status_click">{{ $txt }}</a>
				@endforeach
			</div>
			<div class="msearch fr">
				<select style="width:80px; height:28px; display: none;" name="status" >
					<option value="">全部</option>
					@foreach ($status as $k=>$txt)
						<option value="{{ $k }}"  >{{ $txt }}</option>
					@endforeach
				</select>
				<select style="width:80px; height:28px;" name="field">
					<option value="call_number">手机号</option>
					<option value="customer_name">姓名</option>
					<option value="work_num">工单号</option>
				</select> <input type="text" value="" name="keyWord"/> <button class="btn btn-normal  search_frm ">搜索</button> </div>
		</div>
		</form>
		{{--
		<div class="table-header">
			<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出EXCEL</button>
			<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入EXCEL</button>
		</div>
		--}}
		<table   id="dynamic-table" class="table2">
			<thead>
			<tr>
				<th>工单号</th>
				<th>下单时间</th>
				<th>工单等级</th>
				<th>响应时间</th>
				<th>来电号码</th>
				<th>客户姓名</th>
				<th>客户类别</th>
				<th>客户位置</th>
				<th>来电次数</th>
				<th>上次到访时间</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			{{--
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td>剩余1小时12分</td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn" >结单</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td><span class="red" >超时</a></td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn" >结单</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td> </td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn btn-gray" >查看</a></td>
			</tr>
			<tr>
				<td>34523</td>
				<td>05-22  15:33</td>
				<td>2小时</td>
				<td> </td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td>2</td>
				<td>2018-05-21</td>
				<td><a href="" class="btn btn-gray" >查看</a></td>
			</tr>
			--}}
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="pagination">
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
        const AJAX_URL = "{{ url('api/weixiu/work/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('weixiu/work/add/0') }}"; //添加url
        const SHOW_URL = "{{url('accounts/info/')}}/";//显示页面地址前缀 + id
        const EDIT_URL = "{{url('weixiu/work/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/weixiu/work/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "{{ url('api/weixiu/work/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('weixiu/work/add/0') }}"; //"{{ url('api/weixiu/work/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('weixiu/work/add/0') }}"; //"{{ url('api/weixiu/work/import') }}";//导入EXCEL地址

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>

	<!-- 前端模板部分 -->
	<!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
	<script type="text/template"  id="baidu_template_data_list">

		<%for(var i = 0; i<data_list.length;i++){
		var item = data_list[i];
		{{--var account_status = item.account_status; --}}
		{{--var can_modify = false; --}}
		{{--//&& (item.supplier_status & (1+8))>0--}}
		{{--if( item.issuper==0 ){--}}
		can_modify = false;
		{{--}--}}
		%>

		<tr>
			<td><%=item.work_num%></td>
			<td><%=item.created_at%></td>
			<td><%=item.time_name%></td>
			<td> </td>
			<td><a href="tel:<%=item.call_number%>" class="btn" ><%=item.call_number%> <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
			<td><%=item.customer_name%>(<%=item.sex_text%>)</td>
			<td><%=item.customer_type_name%></td>
			<td><%=item.city_name%>/<%=item.area_name%></td>
			<td><%=item.call_num%></td>
			<td><%=item.last_call_date%></td>
			<td>
            <%if( true){%>
            <a href="javascript:void(0);" class="btn btn-mini btn-success"  onclick="action.show(<%=item.id%>)">
                <i class="ace-icon fa fa-check bigger-60"> 查看</i>
            </a>
            <%}%>
            <%if( can_modify){%>
            <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="action.edit(<%=item.id%>)">
                <i class="ace-icon fa fa-pencil bigger-60"> 编辑</i>
            </a>
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
	{{--<script src="{{ asset('js/weixiu/lanmu/work.js') }}"  type="text/javascript"></script>--}}
@endpush