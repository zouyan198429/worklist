@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加企业</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>公司名称<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" style="width: 80%;" name="company_name" value="{{ $company_name or '' }}" placeholder="公司名称" placeholder=" " autofocus  required />
				</td>
			</tr>
            <tr>
                <th>开通模块编</th>
                <td class="selModuleNos">
                    @foreach ($module_no_kv as $k=>$txt)
                        <label>
                            <input type="checkbox" name="module_nos[]" value="{{ $k }}"  @if( isset($module_no) && ($module_no & $k) == $k) checked="checked"  @endif/>{{ $txt }}
                        </label>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>接线部门<span class="must">*</span></th>
                <td>
                    <select class="wnormal" name="send_work_department_id">
                        <option value="">请选择部门</option>
                        <option value="0"  @if ( 0 == $send_work_department_id ) selected @endif>不指定部门</option>
                        @foreach ($department_kv as $k=>$txt)
                            <option value="{{ $k }}"  @if(isset($send_work_department_id) && $send_work_department_id == $k) selected @endif >{{ $txt }}</option>
                        @endforeach
                    </select>
                    <p>对于【工单】功能的企业，可以指定某个部门的人员来作为接线员！</p>
                    <p>新加企业时，没有数据，要操作可以等企业维护好部门信息后，再来修改！</p>
                    <p>所有人都能接线，则选择【不指定部门】</p>
                </td>
            </tr>
            <tr>
                <th>开启状态<span class="must">*</span></th>
                <td class="sel_open_status">
                    @foreach ($openStatus as $k=>$txt)
                        <label><input type="radio"  name="open_status"  value="{{ $k }}"  @if(isset($open_status) && $open_status == $k) checked="checked"  @endif />{{ $txt }} </label>
                    @endforeach
                </td>
            </tr>

            {{--			<tr>--}}
            {{--				<th>排序[降序]</th>--}}
            {{--				<td>--}}
            {{--					<input type="number" class="inp wnormal"  name="sort_num" onkeyup="isnum(this) " onafterpaste="isnum(this)"  value="{{ $sort_num or '' }}"   placeholder="请输入整数" autofocus  required />--}}
            {{--				</td>--}}
            {{--			</tr>--}}
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
        var SAVE_URL = "{{ url('api/admin/company/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('admin/company')}}";//保存成功后跳转到的地址
	</script>
	<script src="{{ asset('/js/admin/lanmu/company_edit.js') }}?3"  type="text/javascript"></script>
@endpush
