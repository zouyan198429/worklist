@extends('layouts.adminalert')

@push('headscripts')
	{{--  本页单独使用 --}}
	<style type="text/css">
		.right { color: green;font-weight: bold;}
		.wrong {color: red;font-weight: bold;}
		.pink {color: #DB348A;font-weight: bold;}
	</style>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 选择试题</div>
	<div class="mm">
		<div class="mmhead" id="mywork">

			@include('common.pageParams')

			{{--<div class="tabbox" >--}}
				{{--<a href="javascript:void(0);" class="on " onclick="action.add()">添加试题</a>--}}
			{{--</div>--}}
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">
					<select class="wmini" name="subject_type" style="display:none;">
						<option value="">请选择题目类型</option>
						@foreach ($selTypes as $k=>$txt)
							<option value="{{ $k }}"  @if ($k == $defaultSelType) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select style="width:110px; height:28px;"  name="type_id">
						<option value="">请选择分类</option>
						@foreach ($type_kv as $k=>$txt)
							<option value="{{ $k }}"  @if ($k == $default_type_kv) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<input type="text"   name="title" value=""  placeholder="请输入题目"   />
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>
		</div>

		<div class="table-header">
			{{--<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>--}}
			{{--<button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>--}}
			{{--<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcelTemplate(this)">导入模版[EXCEL]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入试题</button>--}}
			{{--<div style="display:none;" ><input type="file" class="import_file img_input"></div>--}}{{--导入file对象--}}
			<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.addBatch(this)">选择试题</button>
		</div>

		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th style="width:70px;">
					<label class="pos-rel">
						<input type="checkbox" class="ace check_all"  value="" onclick="action.seledAll(this)"/>
						<span class="lbl">全选</span>
					</label>
				</th>
				<th>题目</th>
				<th>答案</th>
				<th style="width:80px;">正确答案</th>
				<th style="width:80px;">分类</th>
				<th style="width:70px;">类型</th>
				<th style="width:95px;">添加时间</th>
				<th style="width:95px;">添加人</th>
				<th style="width:80px;">操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class=" pagination">
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
        var LIST_FUNCTION_NAME = "reset_list_self";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/admin/subject/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('admin/subject/add/0') }}"; //添加url
        var SHOW_URL = "{{url('admin/subject/info/')}}/"; //显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('admin/subject/add/')}}/"; //修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/admin/subject/ajax_del') }}"; //删除页面地址
        var BATCH_DEL_URL = "{{ url('api/admin/subject/ajax_del') }}"; //批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('admin/subject/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('admin/subject/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/admin/subject/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

		var SELECTED_IDS = [];// 已经选中的试题id数组
		var SELECTED_SUBJECT_TYPE = "{{ $defaultSelType or 0 }}";// 试题类型
	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/admin/lanmu/subject_select.js') }}"  type="text/javascript"></script>

@endpush
