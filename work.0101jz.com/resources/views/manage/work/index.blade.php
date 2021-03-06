@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 工单管理</div>
	<div class="mm">
		@include('common.pageParams')
		<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm_self" action="#">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" data-status="" class="status_click">全部</a>
                @foreach ($status as $k=>$txt)
                    <a href="javascript:void(0);" data-status="{{ $k }}" class="status_click @if ($k == $defaultStatus) on @endif">
                        {{ $txt }}
                        @if(in_array($k,$countStatus))
                            <span class="layui-badge status_count_{{ $k }}" data-old_count="0">0</span>
                       @endif
                    </a>
                @endforeach
			</div>
			<div class="msearch fr">
				<input type="text"  name="begin_date" class="begin_date" value="{{ $begin_date or '' }}"  placeholder="开始日期" style="width:100px;" />
				--
				<input type="text"  name="end_date" class="end_date" value="{{ $end_date or '' }}"  placeholder="结束日期" style="width:100px;" />
                <select style="width:80px; height:28px; display:none;" name="status" >
                    <option value="">全部</option>
                    @foreach ($status as $k=>$txt)
                        <option value="{{ $k }}" @if ($k == $defaultStatus) selected @endif >{{ $txt }}</option>
                    @endforeach
                </select>
				<select style="width:80px; height:28px;"  name="field">
					<option value="call_number">手机号</option>
					<option value="customer_name">姓名</option>
					<option value="work_num">工单号</option>
				</select> <input type="text" value=""  name="keyWord"  style="width:120px;"/> <button class="btn btn-normal  search_frm_self ">搜索</button>
			</div>
		</div>
		</form>

		<div class="table-header">
			{{--<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>--}}
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcelTemplate(this)">导入模版[EXCEL]</button>--}}
			{{--<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入员工</button>--}}
			<div style="display:none;" ><input type="file" class="import_file img_input"></div>{{--导入file对象--}}
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
				<th width="155px">工单号<br/>来电号码<br/>联系电话</th>
				<th>工单来源</th>
				<th>工单类型</th>
				<th>图片</th>
				<th width="350px">工单内容</th>
				<th>客户位置</th>
				<th>下单时间</th>
				<th>工单等级</th>
				<th>派单人员</th>
				<th>区县客服</th>
				<th>状态</th>
				<!-- 
				<th>客户姓名</th>
				<th>客户类别</th> -->
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody  id="data_list"  class=" baguetteBoxOne gallery">
			{{---
			<tr>
				<td>345236864</td>
				<td>05-22  15:33</td>
				<td>张芸</td>
				<td>雷栋栋</td>
				<td>2小时</td>
				<td>剩余1小时12分</td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
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
    <div style="display:none;">
        @include('public.scan_sound')
    </div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <link rel="stylesheet" href="{{asset('layui-v2.4.3/layui/css/layui.css')}}">
	<script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list_self";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/manage/work/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('manage/work/add/0') }}"; //添加url
        var SHOW_URL = "{{url('manage/work/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "工单详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('manage/work/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/manage/work/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/manage/work/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/work/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/work/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/work/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

		var SATUS_COUNT_URL = "{{ url('api/manage/work/ajax_status_count') }}";// ajax工单状态统计 url
        var NEED_PLAY_STATUS = "{{ $countPlayStatus }}";// 需要发声的状态，多个逗号,分隔

        var FRM_IDS = "search_frm_self";//需要读取的表单的id，多个用,号分隔

        var BEGIN_DATE = "{{ $begin_date or '' }}" ;//开始日期
        var END_DATE = "{{ $end_date or '' }}" ;//结束日期
	</script>
	<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
	<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
	{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}

	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/manage/lanmu/work.js') }}"  type="text/javascript"></script>
@endpush