@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
<script src="{{asset('dist/lib/kindeditor/kindeditor.min.js')}}"></script>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i>  {{ $operate or '' }}通知公告</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
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
					{{--<textarea type="text" class="inptext wlong"  style=" height:500px" /></textarea>
					<p class="tip">根据客户描述，进行记录或备注。</p>--}}
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
        const SAVE_URL = "{{ url('api/manage/notice/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('manage/notice')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/manage/lanmu/notice_edit.js') }}"  type="text/javascript"></script>
@endpush