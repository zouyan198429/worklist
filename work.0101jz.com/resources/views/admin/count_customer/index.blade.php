@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 来电统计</div>
	<div class="mm">
		<div class="tubiao"  >

			{{--<p>客户组成表(企业、个人、...)</p>--}}

			<div id="container1" style="height: 600px;width: 100%"></div>

			{{--<p>客户区域分布表(区域)</p>--}}
			<div id="container" style="height: 600px;width: 100%"></div>


			{{--<p>工单类型表</p>--}}

			<div id="container3" style="height: 600px;width: 100%"></div>

		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
{{--这个js是引用echarts的插件的js--}}
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script src="{{asset('js/common/graph.js')}}"></script>
<script type="text/javascript">

	var id = "container1";
	var data = [
		{value:335, name:'直接访问'},
		{value:310, name:'邮件营销'},
		{value:234, name:'联盟广告'},
		{value:135, name:'视频广告'},
		{value:1548, name:'搜索引擎'}
	];
	var title = '客户组成表';
	var leftTitle = ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'];
	/*
	* id:div中id的值
	* title：图表中正上方的名称
	* data：饼状图中的名称和数据
	* leftTitle: 右上角标识的显示的名称
	* */
	bingzhuangtu(id,title,data,leftTitle);
	var id = "container3";
	var data = [
		{value:335, name:'直接访问'},
		{value:310, name:'邮件营销'},
		{value:234, name:'联盟广告'},
		{value:135, name:'视频广告'},
		{value:1548, name:'搜索引擎'}
	];
	var title = '工单类型表';
	var leftTitle = ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'];

	/*
	 * id:div中id的值
	 * title：图表中正上方的名称
	 * data：饼状图中的名称和数据
	 * leftTitle: 右上角标识的显示的名称
	 * */
	bingzhuangtu(id,title,data,leftTitle);
</script>

<script type="text/javascript">
	var dataAxis = ['点', '击', '柱', '子', '或', '者', '两', '指', '在', '触', '屏', '上', '滑', '动', '能', '够', '自', '动', '缩', '放'];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var id = "container";
	var title = "客户区域分布表(区域)";
	/*
	 * ymax: 柱状图阴影部分的高度(如果数值高于左边的数字，左边的数字也会增长)
	 * dataAxis：柱状图上的显示的文字(一维数组)
	 * data：柱状图上的数值(一维数组)
	 * id：div的id名
	 * */
	zhuzhuangtu(yMax,data,dataAxis,id,title);
</script>
@endpush