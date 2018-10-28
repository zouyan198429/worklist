@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
<style type="text/css">
    .right { color: green;font-weight: bold;}
    .wrong {color: red;font-weight: bold;}
</style>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>

	<div class="mm" id="examinbox">
		<div class="mmhead" >
            场次：{{ $staff_exam['exam_num'] or 0 }} <br />
            考试主题：{{ $staff_exam['exam_subject'] or 0 }}  <br />
            时间：{{ $staff_exam['exam_begin_time'] or 0 }} -- {{ $staff_exam['exam_end_time'] or 0 }}  （{{ $staff_exam['total_score'] or 0 }}分钟）<br />
			进度：<span class="doing_num">1</span>/<span class="count_num">{{ $staff_exam['subject_amount'] or 0 }}</span>  <br />
			剩余时间：
            <span class="back_time">
                <span class="_d">00</span>
                <span class="_h">00</span>
                <span class="_m">00</span>
                <span class="_s">00</span>
                <span class="_ms">00</span>
            </span>
		</div>
        <form class="am-form am-form-horizontal" method="post"  id="addForm">
            <input type="hidden" name="id" value="{{ $id or 0 }}"/>{{--考次的人员company_exam_staff 的id--}}

            <input type="hidden" name="subject_type" value="1">{{--题目类型1单选；2多选；4判断--}}
            <input type="hidden" name="subject_id" value="0">{{--试题company_subject id--}}
            <input type="hidden" name="subject_history_id" value="0">{{--试题历史company_subject_history id--}}
		<div class="content">
			<h1 ><span class="subject_num"></span>、(<span class="subject_type_text"></span>题)<span class="subject_title"></span></h1>
            <ul  id="answer_judge" style="display: none;">
                <li>
                    <label>
                        <input type="radio" name="answer" value="1" checked="checked" /> <span class="right">对</span> &nbsp;&nbsp;
                    </label>
                    <label>
                        <input type="radio" name="answer" value="0"   checked="checked"  /> <span class="wrong">错</span>
                    </label>
                </li>
            </ul>
			<ul  id="data_list"  class="answer_many"  style="display: none;">

			</ul>

		</div>
        </form>
		<div class="mmfoot tc" >
			<a href="javascript:void(0);" class="btn pre_subject" > < 上一题  </a>
			<a href="javascript:void(0);" class="btn next_subject" > 下一题 > </a>
            <a href="javascript:void(0);" class="btn finish">  交  卷  </a>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var INIT_EXAM_URL = "{{ url('api/huawu/exam/ajax_init_exam') }}";// ajax在线考试初始化地址
		var SAVE_URL = "{{ url('api/huawu/exam/doing_ajax_save') }}";// ajax保存记录地址
        var EXAM_STAFF_ID = '{{ $id or 0 }}';// 考试员工表id
        var WIN_URL = "{{ url('huawu/exam/win') }}/" + EXAM_STAFF_ID;// 交卷后跳转地址

        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class
        var EXAM_END_TIME = "{{ $staff_exam['exam_end_time'] or '' }}";// 结束时间

        var SUBMIT_FORM = true;//防止多次点击提交
    </script>
    <script src="{{ asset('/js/huawu/lanmu/exam_doing.js') }}"  type="text/javascript"></script>
@endpush