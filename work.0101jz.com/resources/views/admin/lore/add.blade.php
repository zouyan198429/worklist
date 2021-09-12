@extends('layouts.admin')

@push('headscripts')
	{{--  本页单独使用 --}}
	<script src="{{asset('dist/lib/kindeditor/kindeditor.min.js')}}"></script>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}知识</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>知识分类</th>
				<td>
					<select class="wnormal" name="type_id">
						<option value="">请选择分类</option>
						@foreach ($lore_type_kv as $k=>$txt)
						<option value="{{ $k }}"  @if(isset($type_id) && $type_id == $k) selected @endif>{{ $txt }}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<th>标题</th>
				<td>
					<input type="text" class="inp wlong"  name="title" value="{{ $title or '' }}" placeholder="请输入标题" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>内容<span class="must">*</span></th>
				<td>
					<textarea class="kindeditor" name="content" rows="15" id="doc-ta-1" style=" width:900px;height:400px;">{!!  htmlspecialchars($content ?? '' )   !!}</textarea>
					{{--
					<textarea type="text" class="inptext wlong"  style=" height:500px" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>
					--}}
				</td>
			</tr>
			<tr>
				<th>推荐级别</th>
				<td>
					@foreach ($level_num_kv as $k=>$txt)
					<label>
						<input type="radio" name="level_num" value="{{ $k }}"   @if(isset($level_num) && $level_num == $k) checked="checked"  @endif/>{{ $txt }}
					</label>
					@endforeach
				</td>
			<tr>
			<tr>
				<th>适用岗位</th>
				<td class="selPositionIds">
					@foreach ($position_kv as $k=>$txt)
					<label>
						<input type="checkbox" name="position_ids[]" value="{{ $k }}"  @if( isset($positionIds) && in_array($k, $positionIds)) checked="checked"  @endif/>{{ $txt }}
					</label>
					@endforeach
				</td>
            </tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"  id="submitBtn" >提交</button></td>
			</tr>

		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        const SAVE_URL = "{{ url('api/admin/lore/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('admin/lore')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/admin/lanmu/lore_edit.js') }}?1"  type="text/javascript"></script>
@endpush
