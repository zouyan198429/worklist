@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}考试</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm" onsubmit="return false;">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
			<table class="table1">
				<tr>
					<th>场次</th>
					<td>
						<input type="text" class="inp wlong" name="exam_num" value="{{ $exam_num or '' }}" placeholder="请输入场次" autofocus  required />
					</td>
				</tr>
				<tr>
					<th>考试主题</th>
					<td>
						<input type="text" class="inp wlong" name="exam_subject" value="{{ $exam_subject or '' }}" placeholder="请输入考试主题" autofocus  required />
					</td>
				</tr>
				<tr>
					<th>开考时间</th>
					<td>
						<input type="text" class="inp wlong exam_begin_time" name="exam_begin_time" value="{{ $exam_begin_time or '' }}" placeholder="请选择开考时间" autofocus  required />
					</td>
				</tr>
				<tr>
					<th>最晚开考时间</th>
					<td>
						<input type="text" class="inp wlong exam_begin_time_last" name="exam_begin_time_last" value="{{ $exam_begin_time_last or '' }}" placeholder="请选择最晚开考时间"/>
					</td>
				</tr>
				<tr>
					<th>考试时长(分)</th>
					<td>
						<input type="text" class="inp wlong" name="exam_minute" value="{{ $exam_minute or '' }}" placeholder="请输入考试时长" autofocus  required  onkeyup="isnum(this) " onafterpaste="isnum(this)" />
					</td>
				</tr>
				<tr>
					<th>选择试卷</th>
					<td>
						<span class="paper_name">{{ $paper_name or '' }}</span>
						<input type="hidden" name="paper_id"  value="{{ $paper_id or '' }}" />
						<input type="hidden" name="paper_history_id"  value="{{ $paper_history_id or '' }}" />
						<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectPaper(this)">选择试卷</button>

						<button class="btn btn-danger  btn-xs ace-icon fa fa-pencil bigger-60 update_paper" @if(isset($now_paper) && in_array($now_paper,[0,1])) style="display: none;"  @endif  onclick="otheraction.updatePaper(this)">更新[当前试卷已更新]</button>

					</td>
				</tr>
				<tr>
					<th>及格分数</th>
					<td>
						<input type="text" class="inp wlong" name="pass_score" value="{{ $pass_score or '' }}" placeholder="请输入及格分数" autofocus  required  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>

					</td>
				</tr>
				<tr>
					<th>参与人员</th>
					<td>
						共 <span class="subject_num">{{ $subject_num or '0' }}</span> 人
						<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectStaff(this)">选择人员</button>
					</td>
				</tr>
				<tr>
					<td colspan="2"  class="staff_td">
						<div class="table-header">
							<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="otheraction.batchDel(this, '.staff_td', 'tr')">批量删除</button>
						</div>
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 90px;">
									<label class="pos-rel">
										<input type="checkbox" class="ace check_all" value="" onclick="otheraction.seledAll(this,'.table2')">
										<span class="lbl">全选</span>
									</label>
									<input type="hidden" name="subject_ids[]" value="1502"/>
									<input type="hidden" name="subject_history_ids[]" value="17"/>
								</th>
								<th>工号</th>
								<th>部门/班组</th>
								<th>姓名</th>
								<th>性别</th>
								<th>职务</th>
								<th>手机</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody class="data_list">
							</tbody>

						</table>

					</td>
				</tr>
				<tr>
					<th> </th>
					<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
				</tr>

			</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/admin/exam/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('admin/exam')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_STAFF_URL = "{{ url('api/admin/exam/ajax_get_staff') }}";// ajax初始化参考人员地址
        var AJAX_UPDATE_STAFF_URL = "{{ url('api/admin/exam/ajax_add_staff') }}";// ajax更新参考人员地址
        var AJAX_STAFF_ADD_URL = "{{ url('api/admin/exam/ajax_add_staff') }}";// ajax添加参考人员地址
        var SELECT_STAFF_URL = "{{ url('admin/staff/select') }}";// 选择参考人员地址
        var SELECT_PAPER_URL = "{{ url('admin/paper/select') }}";// 选择试卷地址
        var AJAX_PAPER_ADD_URL = "{{ url('api/admin/exam/ajax_add_paper') }}";// ajax添加/修改试卷地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class


        var BEGIN_TIME = "{{ $exam_begin_time or '' }}" ;//开考时间
        var BEGIN_TIME_LAST = "{{ $exam_begin_time_last or '' }}" ;//最晚开考时间
	</script>
	<script src="{{ asset('/js/admin/lanmu/exam_edit.js') }}"  type="text/javascript"></script>
@endpush