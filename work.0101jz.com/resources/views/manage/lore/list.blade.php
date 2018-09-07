@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线学习</div>
	<div class="mm">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<select class="wnormal">
					<option value="a01">全部</option>
					<option value="a02">维修部</option>
					<option value="a03">话务部</option>
					<option value="a04">行政部</option>
				</select>
			</div>

			<div class="msearch fr"> <input type="text" value=""  /> <button class="btn btn-normal">搜索</button> </div>
		</div>
		<table class="table2">
			<thead>
			<tr>
				<th>标题</th>
				<th>推荐级别</th>
				<th>适用岗位</th>
				<th>上传时间</th>
				<th>上传人</th>
				<th>阅读量</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="tl" ><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i> 企业品牌推广的战略选择</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  网络口碑营销传播的实例独家分析</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  由火狐推广方式的转变谈网站的本地化策略</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  如何创立网络营销品牌？</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  如何利用网络营销提升企业品牌形象</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i> 企业品牌推广的战略选择</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  网络口碑营销传播的实例独家分析</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  由火狐推广方式的转变谈网站的本地化策略</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  如何创立网络营销品牌？</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
			</tr>
			<tr>
				<td class="tl"><a href="{{ url('manage/lore/info') }}" ><i class="fa fa-lemon-o fa-fw" aria-hidden="true"></i>  如何利用网络营销提升企业品牌形象</a></td>
				<td>★★</td>
				<td>话务</td>
				<td>张兰兰</td>
				<td>2015-04-22</td>
				<td>233</td>
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