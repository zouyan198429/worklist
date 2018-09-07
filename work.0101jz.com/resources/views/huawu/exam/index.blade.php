@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>

	<div class="mm">
		<div class="mmhead tabbox" id="mywork">
			<a href="#" class="on">近期考试</a>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th></th>
				<th>场次</th>
				<th>考试主题</th>
				<th>开考日期</th>
				<th>开考时间</th>
				<th>结束时间</th>
				<th>考试时长</th>
				<th>考试岗位</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>距离开考：2天3小时33分钟</td>
				<td></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>考试中...</td>
				<td><a href="{{ url('huawu/exam/doing') }}" class="btn" >进入</a></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>完成</td>
				<td><a href="examin_cj.html" class="btn" >查看成绩</a></td>
			</tr>

			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>完成</td>
				<td><a href="examin_cj.html" class="btn" >查看成绩</a></td>
			</tr>


			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>完成</td>
				<td><a href="examin_cj.html" class="btn" >查看成绩</a></td>
			</tr>

			<tr>
				<td><input type="checkbox" name="vehicle" value="11" /></td>
				<td>1002455</td>
				<td>维修业务知识测评</td>
				<td>2018-05-21</td>
				<td>09:30</td>
				<td>11:00</td>
				<td>90分钟</td>
				<td>话务</td>
				<td>完成</td>
				<td><a href="examin_cj.html" class="btn" >查看成绩</a></td>
			</tr>
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="mmfright pages">
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush