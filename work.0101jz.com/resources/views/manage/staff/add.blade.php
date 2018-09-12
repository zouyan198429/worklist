@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加员工</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>工号<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal"  name="work_num" value="{{ $work_num or '' }}" placeholder="请输入工号" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>部门/班组</th>
				<td>

					<select class="wnormal" name="department_id">
						<option value="">请选择部门</option>
						@foreach ($department_kv as $k=>$txt)
							<option value="{{ $k }}"  @if(isset($department_id) && $department_id == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					</select>
					<select class="wnormal" name="group_id">
						<option value="">请选择</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>职务</th>
				<td>
					<select class="wnormal">
						<option value="a01">部门</option>
						<option value="a02">宽带业务</option>
						<option value="a03">手机业务</option>
						<option value="a04">其他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>姓名<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal"  name="real_name" value="{{ $real_name or '' }}" placeholder="请输入姓名" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>性别</th>
				<td>
					<input type="radio" name="sex" value="1" @if (isset($real_name) && $real_name == 1 ) checked @endif>男&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="sex" value="2" @if (isset($real_name) && $real_name == 2 ) checked @endif>女
				</td>
			</tr>
			<tr>
				<th>座机电话</th>
				<td>
					<input type="number" class="inp wnormal"  name="tel" value="{{ $tel or '' }}" placeholder="请输入座机电话" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>手机</th>
				<td>
					<input type="number" class="inp wnormal"  name="mobile" value="{{ $mobile or '' }}" placeholder="请输入手机" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>QQ</th>
				<td>
					<input type="number" class="inp wnormal"  name="qq_number" value="{{ $qq_number or '' }}" placeholder="请输入QQ" autofocus  required />
				</td>
			</tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"  id="submitBtn" >提交</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')

	<script type="text/javascript">
        const SAVE_URL = "{{ url('api/manage/stall/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('manage/stall/list')}}";//保存成功后跳转到的地址
		const REL_CHANGE = {
		    'department':{
		        'child_sel_name': 'group_id',// 第二级下拉框的name
                'child_sel_txt': {'': "请选择小组" },// 第二级下拉框的{值:请选择文字名称}
                'change_ajax_url': "{{ url('api/manage/staff/ajax_get_child') }}",// 获取下级的ajax地址
				'parent_param_name': 'parent_id',// ajax调用时传递的参数名
				'other_params':{},//其它参数 {'aaa':123,'ccd':'dfasfs'}
			}
		};
        $(function(){
            //当前部门小组
			@if ($department_id >0 )
              changeFirstSel(REL_CHANGE.department,"{{ $department_id or 0}}","{{ $group_id or 0 }}");
			@endif
            //部门值变动
            $(document).on("change",'select[name=department_id]',function(){
                changeFirstSel(REL_CHANGE.department, $(this).val(), 0);
                return false;
            });
        });
	</script>
	<script src="{{ asset('/js/manage/lanmu/stall_edit.js') }}"  type="text/javascript"></script>
@endpush