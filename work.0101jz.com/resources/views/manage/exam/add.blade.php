@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 试题添加</div>
	<div class="mm">

		<table class="table1">
			<tr>
				<th>场次</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考试主题</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>开考时间</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考试时长</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>选择试卷</th>
				<td>
					<a href="" class="btn btn-gray" >试卷列表..</a>
				</td>
			</tr>
			<tr>
				<th>及格分数</th>
				<td>
					<input type="text" class="inp wlong" value="" placeholder="" autofocus  required />

				</td>
			</tr>
			<tr>
				<th>参与人员</th>
				<td>
					共 150 人
					<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="action.batchDel(this)">选择人员</button>
				</td>
			</tr>
			<tr>
				<td colspan="2">
                    <?php for($i=1;$i<=3;$i++) { ?>
					<div style="margin-top: 20px;">
						<div class="table-header">
							<div>
								甘谷县/新兴片区 ：共 150 人
								<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="action.batchDel(this)">选择人员</button>
							</div>
							<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="action.batchDel(this)">批量删除</button>
						</div>
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 90px;">
									<label class="pos-rel">
										<input type="checkbox" class="ace check_all" value="" onclick="action.seledAll(this)">
										<span class="lbl">全选</span>
									</label>
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
							<tbody id="data_list">
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="action.seledSingle(this)" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
								</td>
								<td>38000592</td>
								<td>甘谷县/新兴片区</td>
								<td>孙昌义</td>
								<td>未知</td>
								<td>营业员</td>
								<td>15025978154</td>
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
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="action.seledSingle(this)" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
								</td>
								<td>38000592</td>
								<td>甘谷县/新兴片区</td>
								<td>孙昌义</td>
								<td>未知</td>
								<td>营业员</td>
								<td>15025978154</td>
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
						</table>
					</div>
                    <?php }?>
				</td>
			</tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal" >提交</button></td>
			</tr>

		</table>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush
