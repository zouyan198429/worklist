@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 客户管理</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<div class="tabbox" >
				@foreach ($selTypes as $k=>$txt)
				<a href="javascript:void(0)"  data-selType="{{ $k }}" class="selType_click @if ($k == $defaultSelType) on @endif">
					{{ $txt }}
				</a>
				@endforeach
			</div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">
					<select style="width:80px; height:28px;display: none;" name="sel_type" >
						{{--<option value="">全部</option>--}}
						@foreach ($selTypes as $k=>$txt)
							<option value="{{ $k }}"   @if ($k == $defaultSelType) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wmini" name="field">
						{{--<option value="">全部</option>--}}
						{{--<option value="customer_name">客户姓名</option>--}}
						<option value="call_number">来电电话</option>
						<option value="contact_number">联系电话</option>
					</select>
					<input type="text" value=""  name="keyWord" />
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>


			<table  id="dynamic-table" class="table2">
				<thead>
				<tr>
					<th>来电号码</th>
					<th>联系电话</th>
					{{--
					<th>客户姓名</th>
					<th>客户类别</th>
					--}}
					<th>客户位置</th>
					<th>来电次数</th>
					<th>上次到访时间</th>
					<th>标记</th>
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
    var AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
    var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
	var AJAX_URL = "{{ url('api/admin/customer/ajax_alist') }}";//ajax请求的url
	var ADD_URL = "{{ url('admin/customer/add/0') }}";//添加url
	var SHOW_URL = "{{url('admin/customer/info/')}}/"; //显示页面地址前缀 + id
    var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
    var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
	var EDIT_URL = "{{url('admin/customer/add/')}}/" ; //修改页面地址前缀 + id
	var DEL_URL = "{{ url('api/admin/customer/ajax_del') }}";  //删除页面地址
	var BATCH_DEL_URL = "{{ url('api/admin/customer/ajax_del') }}";  //批量删除页面地址
	var EXPORT_EXCEL_URL = "{{ url('admin/customer/export') }}";//导出EXCEL地址
    var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('admin/customer/import_template') }}";//导入EXCEL模版地址
	var IMPORT_EXCEL_URL = "{{ url('api/admin/customer/import') }}";//导入EXCEL地址
    var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

    var TAG_URL = "{{ url('api/admin/customer/ajax_is_tab') }}";//ajax请求标记的url

</script>
<script src="{{asset('js/common/list.js')}}"></script>
<script src="{{ asset('js/admin/lanmu/customer.js') }}"  type="text/javascript"></script>
@endpush