@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 知识分类</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加分类</a>
			</div>
		</div>
		<table id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>分类</th>
				<th>排序</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody id="data_list">
			</tbody>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/admin/lore_type/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('admin/lore_type/add/0') }}"; //添加url
        var SHOW_URL = "{{url('lore_type/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('admin/lore_type/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/admin/lore_type/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/manage/lore_type/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/lore_type/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/lore_type/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/lore_type/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('/js/admin/lanmu/lore_type.js') }}"  type="text/javascript"></script>
@endpush