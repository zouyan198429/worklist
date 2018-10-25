@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">学习中心</div>
		</div>
		@include('common.pageParams')
		<div class="tab">
				<a href="javascript:void(0);"  data-type_id="" class="type_click on">全部</a>

				@foreach ($lore_type_kv as $k=>$txt)
					<a href="javascript:void(0);" data-type_id="{{ $k }}"  class=" type_click  @if ($k == $defaultType) on @endif" >
						{{ $txt }}
					</a>
				@endforeach
		</div>
		<form style="display: none;" onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
			<select style="width:80px; height:28px;" name="type_id" >
				<option value="">全部</option>
				@foreach ($lore_type_kv as $k=>$txt)
					<option value="{{ $k }}" @if ($k == $defaultType) selected @endif >{{ $txt }}</option>
				@endforeach
			</select>
			<button class="btn btn-normal  search_frm ">搜索</button>
		</form>
		<section class="main"  id="dynamic-table" {{--id="study"--}} >
			<ul class="listtext" id="data_list">
				{{--
				<li>
					<a href="{{ url('m/lore/info') }}" >
						<div class="title" >
							<p><i class="fa fa-angle-right  fa-fw" aria-hidden="true"></i> 企业品牌推的战略广的战略选择</p>
							<span>★★</span>
						</div>
						<div class="view"><i class="fa fa-eye fa-fw" aria-hidden="true"></i> 2133</div>
					</a>
				</li>
				--}}

			</ul>

		</section>
		<div class="fd ">
			<div class="pagination"></div>
		</div>
		@include('mobile.layout_public.menu', ['menu_id' => 2])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = true;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/m/lore/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('m/lore/add/0') }}"; //添加url
        var SHOW_URL = "{{url('m/lore/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "知识详情" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 2 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('m/lore/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/m/lore/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/m/lore/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('m/lore/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('m/lore/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/m/lore/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

        var DYNAMIC_LODING_BAIDU_TEMPLATE = "baidu_template_data_loding_li";//加载中百度模板id
        var DYNAMIC_BAIDU_EMPTY_TEMPLATE = "baidu_template_data_empty_li";//没有数据记录百度模板id
	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('js/m/lanmu/lore.js') }}"  type="text/javascript"></script>
@endpush