@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
<style type="text/css">
	.right { color: green;font-weight: bold;}
	.wrong {color: red;font-weight: bold;}
    .pink {color: #DB348A;font-weight: bold;}
</style>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}试卷</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm" onsubmit="return false;">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>试卷名称</th>
				<td>
					<input type="text" class="inp wlong" name="paper_name" value="{{ $paper_name or '' }}" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>试题顺序</th>
				<td>
					@foreach ($selTypes as $k=>$txt)
					<label>
						<input type="radio" name="subject_order_type" value="{{ $k }}"  @if(isset($subject_order_type) && $subject_order_type == $k) checked="checked"  @endif/>{{ $txt }}
					</label>
					@endforeach
				</td>
			</tr>
			<tr>
				<th>试题</th>
				<td>
					<span class="subject_amount">{{ $subject_amount or '0' }}</span> 题
				</td>
			</tr>
			<tr>
				<th>总分</th>
				<td>
					<span class="total_score">{{ $total_score or '0' }}</span> 分
				</td>
			</tr>

			<tr>
				<td colspan="2" class="subject_td">
					@foreach ($subjectTypes as $type)
					<div style="margin-top: 20px;" class="subject_types  subject_type_{{ $type['type_id'] or 0 }}">
						<input type="hidden" name="type_id[]" value="{{ $type['type_id'] or 0 }}"/>
                        <input type="hidden" name="type_name[]" value="{{ $type['type_name'] or 0 }}"/>
						<div class="table-header">
							<div>
								{{ $type['type_name'] or '' }} ：共
                                <input type="hidden" name="subject_count[]"  value="{{ $type['subject_count'] or '0' }}" style="width:40px;"  onkeyup="isnum(this) " onafterpaste="isnum(this)" />
                                <span class="subject_count">{{ $type['subject_count'] or '0' }}</span>
                                题，

                                总分 <input type="text" name="subject_score[]"  value="{{ $type['subject_score'] or '0' }}" style="width:50px;"  onkeyup="numxs(this) " onafterpaste="numxs(this)" /> 分
								{{--<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="action.batchDel(this)">随机生成试题</button>--}}
								<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.select(this,{{ $type['type_id'] or 0 }})">选择试题</button>
							</div>
							<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="otheraction.batchDel(this, '.subject_types', 'tr')">批量删除</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-up bigger-60"  onclick="otheraction.moveUp(this, '.subject_types')" >上移</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-down bigger-60"  onclick="otheraction.moveDown(this, '.subject_types')" >下移</button>
						</div>
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 90px;">
									<label class="pos-rel">
										<input type="checkbox" class="ace check_all" value="" onclick="otheraction.seledAll(this,'.table2')">
										<span class="lbl">全选</span>
									</label>

								</th>
								<th >试题  </th>
								<th>答案</th>
								<th>正确答案</th>
								<th>分类</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody class="data_list" >
                            {{--
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
                                    <input type="hidden" name="subject_ids[]" value="1"/>
                                    <input type="hidden" name="subject_history_ids[]" value="1"/>
								</td>
								<td>
									1、我是邹燕吗?aaaaaa
								</td>
								<td align="center">
									A、1+1   <span class="wrong">×</span><br>
									B、32+40   <span class="right">√</span><br>
									C、22+10   <span class="right">√</span><br>
									D、50+40   <span class="right">√</span>
								</td>
								<td>
									B、C、D
								</td>
								<td>
									营销知识
								</td>
								<td>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this, 'tr')">
										<i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this, 'tr')">
										<i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.edit(1)">
										<i class="ace-icon fa fa-pencil bigger-60"> 编辑</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
										<i class="ace-icon fa fa-trash-o bigger-60"> 移除</i>
									</a>
								</td>
							</tr>
							--}}
						</table>
					</div>
					@endforeach
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
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/manage/paper/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('manage/paper')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_SUBJECT_URL = "{{ url('api/manage/paper/ajax_get_subject') }}";// ajax初始化试题地址
        var AJAX_UPDATE_SUBJECT_URL = "{{ url('api/manage/paper/ajax_update_subject') }}";// ajax更新试题地址
        var AJAX_SUBJECT_ADD_URL = "{{ url('api/manage/paper/ajax_add_subject') }}";// ajax添加试题地址
        var SELECT_SUBJECT_URL = "{{ url('manage/subject/select') }}";// 选择试题地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class

	</script>
	<script src="{{ asset('/js/manage/lanmu/paper_edit.js') }}"  type="text/javascript"></script>
@endpush