@extends('layouts.managealert')

@push('headscripts')
{{--  本页单独使用 --}}
<style type="text/css">
	.right { color: green;font-weight: bold;}
	.wrong {color: red;font-weight: bold;}
	.pink {color: #DB348A;font-weight: bold;}
</style>
@endpush

@section('content')

	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 选择员工</div>--}}
	<div class="mm">
		<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
		<div class="mmhead" id="mywork">

			@include('common.pageParams')

			<div class="tabbox"  style="width:880px;">
				{{--<a href="javascript:void(0);" class="on " onclick="action.add()">添加员工</a>--}}
				职位：
				@foreach ($position_kv as $k=>$txt)
					<label>
						<input type="checkbox" name="position_ids" value="{{ $k }}"  @if( isset($positionIds) && in_array($k, $positionIds)) checked="checked"  @endif/>{{ $txt }}
					</label>
				@endforeach
			</div>

			<div class="msearch fr"  style="width:880px;">
				<select class="wmini" name="department_id">
					<option value="">全部</option>
					@foreach ($department_kv as $k=>$txt)
						<option value="{{ $k }}"  @if(isset($department_id) && $department_id == $k) selected @endif >{{ $txt }}</option>
					@endforeach
				</select>
				<select class="wmini" name="group_id">
					<option value="">请选择班组</option>
				</select>
				<select class="wmini" name="field">
					{{--<option value="">全部</option>--}}
					{{--<option value="customer_name">客户姓名</option>--}}
					<option value="real_name">姓名</option>
					<option value="mobile">手机</option>
					<option value="work_num">工号</option>
				</select>
				<input type="text" value=""  name="keyWord" />
				<button class="btn btn-normal search_frm " >搜索</button>
			</div>
		</div>
		</form>
		<div style="clear:both;"></div>
		<div class="table-header">
			{{--<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>--}}
			{{--<button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>--}}
			{{--<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcelTemplate(this)">导入模版[EXCEL]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入员工</button>--}}
			{{--<div style="display:none;" ><input type="file" class="import_file img_input"></div>--}}{{--导入file对象--}}
			<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.addBatch(this)">选择员工(勾选)</button>
			<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.addBatchSearch(this)">选择员工(查询条件)</button>
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
				{{--<th>电话</th>--}}
				<th>手机</th>
				{{--<th>QQ</th>--}}
				<th>操作</th>
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
        var AJAX_URL = "{{ url('api/manage/staff/ajax_alist') }}";//ajax请求的url
		var ADD_URL = "{{ url('manage/staff/add/0') }}"; //添加url
        var SHOW_URL = "{{url('manage/staff/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('manage/staff/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//删除页面地址
		var BATCH_DEL_URL = "{{ url('api/manage/staff/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/staff/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/staff/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/staff/import') }}";//导入EXCEL地址
		var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

        var SELECTED_IDS = [];// 已经选中的试题id数组
        var AJAX_SEARCH_IDS_URL = "{{ url('api/manage/staff/ajax_get_ids') }}";//ajax请求的url --获得查询所有记录的id字符串，多个逗号分隔

        const REL_CHANGE = {
            'department':{
                'child_sel_name': 'group_id',// 第二级下拉框的name
                'child_sel_txt': {'': "请选择班组" },// 第二级下拉框的{值:请选择文字名称}
                'change_ajax_url': "{{ url('api/manage/department/ajax_get_child') }}",// 获取下级的ajax地址
                'parent_param_name': 'parent_id',// ajax调用时传递的参数名
                'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
            }
        };
        $(function(){
            //当前部门小组
			@if (isset($department_id) && $department_id >0 )
            changeFirstSel(REL_CHANGE.department,"{{ $department_id or 0}}","{{ $group_id or 0 }}", true);
			@endif
            //部门值变动
            $(document).on("change",'select[name=department_id]',function(){
                changeFirstSel(REL_CHANGE.department, $(this).val(), 0, true);
                return false;
            });
        });
	</script>
    <script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/manage/lanmu/staff_select.js') }}"  type="text/javascript"></script>
@endpush
