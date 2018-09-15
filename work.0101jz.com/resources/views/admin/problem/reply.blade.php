@extends('layouts.adminalert')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈回复</div>--}}
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
			<table class="table1">
				<tr >
					<th> 内容</th>
					<td>
						{!!  $content or '' !!}
					</td>
				</tr>
				<tr>
					<th>回复内容<span class="must">*</span></th>
					<td>
						<textarea type="text" class="inptext wlong" name="reply_content" />{{ $reply_content or '' }}</textarea>
						<p class="tip"></p>
					</td>
				</tr>

			</table>
			<div class="line"> </div>
			<table class="table1">
				<tr>
					<th> </th>
					<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
				</tr>
			</table>
			<div class="line"> </div>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')

	<script type="text/javascript">
        const SAVE_URL = "{{ url('api/admin/problem/reply_ajax_save') }}";// ajax保存记录地址
        // const LIST_URL = "{{url('admin/problem')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('js/admin/lanmu/problem_reply.js') }}"  type="text/javascript"></script>
@endpush