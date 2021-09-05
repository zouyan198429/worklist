@extends('layouts.weixiu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈问题</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			{{--<div class="tabbox" >--}}
					{{--<button class="btn btn-normal search_frm " >导出EXECL</button>--}}
			{{--</div>--}}
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
				<div class="msearch fr">
					<select class="wmini" name="status" >
						<option value="">全部状态</option>
						@foreach ($status_kv as $k=>$txt)
							<option value="{{ $k }}"  @if ($k == $defaultStatus) selected @endif  >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wmini" name="work_type_id" >
						<option value="">问题分类</option>
						@foreach ($problem_type_kv as $k=>$txt)
							<option value="{{ $k }}"  >{{ $txt }}</option>
						@endforeach
					</select>
					<input type="text" value=""   name="keyWord" placeholder="问题内容" />
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>
		</div>
		<div class="table-header">
			{{--<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>--}}
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcelTemplate(this)">导入模版[EXCEL]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入</button>--}}
			{{--<div style="display:none;" ><input type="file" class="import_file img_input"></div>--} }{ {  --导入file对象--}}
		</div>

		<table   id="dynamic-table" class="table2">
			<thead>
			<tr>
				<th width="80">
					<label class="pos-rel">
						<input type="checkbox" class="ace check_all"  value="" onclick="action.seledAll(this)"/>
						<span class="lbl">全选</span>
					</label>
				</th>
				<th width="200">问题分类</th>
				<th>图片</th>
				<th>内容</th>
				<th>回复</th>
				{{--<th>客户电话</th>
				<th>地址</th>--}}
				<th width="140">发送部门</th>
				<th width="140">发送人</th>
				<th width="140">时间</th>
				<th>状态</th>
				{{--<th>操作</th>--}}
			</tr>
			</thead>
			<tbody id="data_list" class=" baguetteBoxOne gallery">
		{{--	<tr>
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
			--}}




			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class=" pagination">
				{{--<a href="" class="on" > - </a>--}}
				{{--<a href="" > 1 </a>--}}
				{{--<a href=""> 2 </a>--}}
				{{--<a href=""> 4 </a>--}}
				{{--<a href=""> 5 </a>--}}
				{{--<a href=""> > </a>--}}
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
	var AJAX_URL = "{{ url('api/weixiu/problem/ajax_alist') }}";//ajax请求的url
	var ADD_URL = "{{ url('weixiu/problem/add/0') }}"; //添加url
	var SHOW_URL = "{{url('weixiu/problem/info/')}}/"; //显示页面地址前缀 + id
    var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
    var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
	var EDIT_URL = "{{url('weixiu/problem/add/')}}/"; //修改页面地址前缀 + id
	var DEL_URL = "{{ url('api/weixiu/problem/ajax_del') }}"; //删除页面地址
	var BATCH_DEL_URL = "{{ url('api/weixiu/problem/ajax_del') }}"; //批量删除页面地址
	var EXPORT_EXCEL_URL = "{{ url('weixiu/problem/export') }}";//导出EXCEL地址
    var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('weixiu/problem/import_template') }}";//导入EXCEL模版地址
	var IMPORT_EXCEL_URL = "{{ url('api/weixiu/problem/import') }}";//导入EXCEL地址
    var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

    var REPLY_URL = "{{ url('weixiu/problem/reply/')}}/";// 回复地址
    var REPLY_TITLE = "回复";


</script>
<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}

<script src="{{asset('js/common/list.js')}}"></script>
<script src="{{ asset('/js/weixiu/lanmu/problem.js') }}"  type="text/javascript"></script>
@endpush



