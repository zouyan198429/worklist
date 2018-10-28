@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>
	<div class="mm">

		<div class="mmhead" id="mywork">
			@include('common.pageParams')
			<div class="tabbox" >
				@foreach ($selStatus as $k=>$txt)
					<a href="javascript:void(0)"  data-selstatus="{{ $k }}" class="selStatus_click @if ($k == $defaultSelStatus) on @endif">
						{{ $txt }}
					</a>
				@endforeach
			</div>
			<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#" style="display: none;">
				<div class="msearch fr">
					<select style="width:80px; height:28px;display: none;" name="status" >
						{{--<option value="">全部</option>--}}
						@foreach ($selStatus as $k=>$txt)
							<option value="{{ $k }}"   @if ($k == $defaultSelStatus) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wmini" name="field">
						{{--<option value="">全部</option>--}}
						<option value="exam_num">场次</option>
						<option value="exam_subject">考试主题</option>
					</select>
					<input type="text" value=""  name="keyWord" />
					<button class="btn btn-normal search_frm " >搜索</button>
				</div>
			</form>

			<table  id="dynamic-table" class="table2">
				<thead>
				<tr>
					<th>场次</th>
					<th>考试主题</th>
					<th>开考时间</th>
					<th>考试时长</th>
					<th>结束时间</th>
					<th>题数</th>
					<th>总分<br/>及格分</th>
					<th>分数</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody id="data_list">
				{{--
				<tr>
					<td><input type="checkbox" name="vehicle" value="11" /></td>
					<td>1002455</td>
					<td>维修业务知识测评</td>
					<td>2018-05-21</td>
					<td>09:30</td>
					<td>11:00</td>
					<td>90分钟</td>
					<td>话务</td>
					<td>
						距离开考：2天3小时33分钟
						<a href="{{ url('huawu/exam/doing') }}" class="btn" >进入</a>
						<a href="examin_cj.html" class="btn" >查看成绩</a>
					</td>
					<td></td>
				</tr>
				--}}
				</tbody>
			</table>
			<div class="mmfoot">
				<div class="mmfleft"></div>
				<div class="pagination">
				</div>
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
        var AJAX_URL = "{{ url('api/huawu/exam/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('huawu/exam/add/0') }}"; //添加url
        var SHOW_URL = "{{url('huawu/exam/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "通知公告详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('huawu/exam/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/huawu/exam/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/huawu/exam/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('huawu/exam/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('huawu/exam/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/huawu/exam/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

		var DOING_EXAM_URL = "{{ url('huawu/exam/doing/') }}/";// 进入答题

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/huawu/lanmu/exam.js') }}"  type="text/javascript"></script>
@endpush