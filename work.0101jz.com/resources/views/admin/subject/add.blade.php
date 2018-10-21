@extends('layouts.admin')

@push('headscripts')
	{{--  本页单独使用 --}}
	<style type="text/css">
		.right { color: green;font-weight: bold;}
		.wrong {color: red;font-weight: bold;}
	</style>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}试题</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
			<table class="table1">
				<tr>
					<th>分类</th>
					<td>
						<select class="wnormal" name="type_id" >
							<option value="">请选择分类</option>
							@foreach ($type_kv as $k=>$txt)
								<option value="{{ $k }}"  @if (isset($type_id) && $k == $type_id) selected @endif >{{ $txt }}</option>
							@endforeach
						</select>
					</td>
				</tr>
				<tr>
					<th>类型</th>
					<td>
						@foreach ($selTypes as $k=>$txt)
							<label>
								<input type="radio" name="subject_type" value="{{ $k }}"  @if(isset($subject_type) && $subject_type == $k) checked="checked"  @endif />{{ $txt }}
							</label>
						@endforeach
					</td>
				</tr>
				<tr>
					<th>题目</th>
					<td>
						<textarea type="text" class="inptext wlong" name="title"  style=" height:100px"  placeholder="请输入题目"/>{{ $title or '' }}</textarea>
					</td>
				</tr>
				<tr class="answer_judge">
					<th>答案</th>
					<td>
						<label>
							<input type="radio" name="answer" value="1"  @if(isset($answer) && $answer == '1') checked="checked"  @endif /> <span class="right">对</span> &nbsp;&nbsp;
						</label>
						<label>
							<input type="radio" name="answer" value="0"  @if(isset($answer) && $answer == '0') checked="checked"  @endif /> <span class="wrong">错</span>
						</label>
					</td>
				</tr>
				<tr class="answer_many">
					<td colspan="2" >
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 720px;">答案</th>
								<th >正确答案</th>
								<th >操作</th>
							</tr>
							</thead>
							<tbody id="data_list">
							{{--
                            <tr>
                                <td>
                                    <input type="hidden" name="answer_id[]" value="{{ $v['id'] or 0 }}"/>
                                    <span class="colum"></span>、<input type="text" name="answer_content[]" class="inp wlong" value="{{ $v['answer_content'] or '' }}"/>
                                </td>
                                <td align="center">
                                    <input type="radio" name="answer_val" value=""  @if(isset($v['is_right']) && $v['is_right'] == 1) checked="checked"  @endif />
                                    <input type="checkbox" class="check_answer" name="check_answer_val[]" value="" @if(isset($v['is_right']) && $v['is_right'] == 1) checked="checked"  @endif/>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this)">
                                        <i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this)">
                                        <i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
                                    </a>

                                    <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this)">
                                        <i class="ace-icon fa fa-trash-o bigger-60"> 移除</i>
                                    </a>
                                </td>
                            </tr>
                            --}}
							</tbody>
							<tfoot>
							<tr>
								<td colspan="3" style="text-align:right;">
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.add()"  style="margin-right: 132px;">
										<i class="ace-icon fa fa-plus bigger-60"> 增加答案</i>
									</a>
								</td>
							</tr>

							</tfoot>
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
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/admin/subject/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('admin/subject')}}";//保存成功后跳转到的地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表id
        var ANSWER_LIST = @json($answer_list) ;
        var ANSWER_DATA_LIST = {
            'data_list':ANSWER_LIST
        };
        var DEFAULT_DATA_LIST = [{'id':0, 'answer_content':'', 'is_right':0,}];// 默认答案
	</script>
	<script src="{{ asset('/js/admin/lanmu/subject_edit.js') }}"  type="text/javascript"></script>

@endpush