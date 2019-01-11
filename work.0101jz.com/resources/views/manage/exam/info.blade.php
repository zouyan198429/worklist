@extends('layouts.managealert')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 考试结果</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm" onsubmit="return false;">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			{{--<tr>--}}
				{{--<th>场次</th>--}}
				{{--<td>--}}
					{{--{{ $exam_num or '' }}--}}
				{{--</td>--}}
			{{--</tr>--}}
			<tr>
				<th>考试主题</th>
				<td>
					{{ $exam_subject or '' }}
				</td>
			</tr>
			<tr>
				<th>开考时间</th>
				<td>
					{{ $exam_begin_time or '' }}
				</td>
			</tr>
			<tr>
				<th>考试时长(分)</th>
				<td>
					{{ $exam_minute or '' }}分钟
				</td>
			</tr>
			<tr>
				<th>结束时间</th>
				<td>
					{{ $exam_end_time or '' }}
				</td>
			</tr>
			<tr>
				<th>选择试卷</th>
				<td>
					<span class="paper_name">{{ $paper_name or '' }}</span>
				</td>
			</tr>
			<tr>
				<th>总分数/题数</th>
				<td>
					{{ $total_score or '' }}分/{{ $subject_amount or '' }}题
				</td>
			</tr>
			<tr>
				<th>及格分数</th>
				<td>
					{{ $pass_score or '' }}

				</td>
			</tr>
			<tr>
				<th>参与人员</th>
				<td>
					共 <span class="subject_num">{{ $subject_num or '0' }}</span> 人

				</td>
			</tr>
			<tr>
				<td colspan="2"  class="staff_td">
					<div class="table-header">
						<button class="btn btn-success  btn-xs export_excel"  onclick="otheraction.batchExportExcel(this)" >导 出</button>
					</div>
					<table class="table2">
						<thead>
						<tr>
							<th>工号</th>
							<th>部门/班组</th>
							<th>姓名</th>
							<th>性别</th>
							<th>职务</th>
							<th>手机</th>
							<th>答题时间</th>
							<th>答题用时(分)</th>
							<th>分数</th>
						</tr>
						</thead>
						<tbody class="data_list">
						</tbody>

					</table>

				</td>
			</tr>
		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/manage/exam/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('manage/exam')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_STAFF_URL = "{{ url('api/manage/exam/ajax_get_staff') }}";// ajax初始化参考人员地址
        var AJAX_UPDATE_STAFF_URL = "{{ url('api/manage/exam/ajax_add_staff') }}";// ajax更新参考人员地址
        var AJAX_STAFF_ADD_URL = "{{ url('api/manage/exam/ajax_add_staff') }}";// ajax添加参考人员地址
        var SELECT_STAFF_URL = "{{ url('manage/staff/select') }}";// 选择参考人员地址
        var SELECT_PAPER_URL = "{{ url('manage/paper/select') }}";// 选择试卷地址
		var AJAX_PAPER_ADD_URL = "{{ url('api/manage/exam/ajax_add_paper') }}";// ajax添加/修改试卷地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class


        var EXPORT_EXCEL_URL = "{{ url('manage/exam/exportStaff') }}";//导出EXCEL地址
        var BEGIN_TIME = "{{ $exam_begin_time or '' }}" ;//开考时间
	</script>
	<script src="{{ asset('/js/manage/lanmu/exam_info.js') }}"  type="text/javascript"></script>
@endpush
