@extends('layouts.weixiualert')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈回复</div>--}}
	<div class="mm">
		@include('weixiu.work.publicinfo')
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
			<table class="table1">
				<tr>
					<th>反馈<span class="must">*</span></th>
					<td>
						<textarea type="text" class="inptext wlong" name="win_content" />{{ $win_content or '' }}</textarea>
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
        var SAVE_URL = "{{ url('api/weixiu/work/ajax_win') }}";// ajax保存记录地址
        // var LIST_URL = "{{url('weixiu/work')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('js/weixiu/lanmu/work_win.js') }}"  type="text/javascript"></script>
@endpush