@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}

<script src="{{asset('dist/lib/jquery-qrcode-master/jquery.qrcode.min.js')}}"></script>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 企业</div>
	<div class="mm">
		@include('common.pageParams')
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" class="on"  onclick="action.add()">添加企业</a>
			</div>

            <form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
                <div class="msearch fr">
                    <select class="wmini" name="open_status">
                        <option value="">请选择开启状态</option>
                        @foreach ($openStatus as $k=>$txt)
                            <option value="{{ $k }}">{{ $txt }}</option>
                        @endforeach
                    </select>

                    <select class="wmini" name="company_status">
                        <option value="">请选择公司状态</option>
                        @foreach ($companyStatus as $k=>$txt)
                            <option value="{{ $k }}">{{ $txt }}</option>
                        @endforeach
                    </select>

                    <select class="wmini" name="module_no">
                        <option value="">请选择开通模块</option>
                        @foreach ($module_no_kv as $k=>$txt)
                            <option value="{{ $k }}">{{ $txt }}</option>
                        @endforeach
                    </select>

                    <select class="wmini" name="field">
                        {{--<option value="">全部</option>--}}
                        {{--<option value="customer_name">客户姓名</option>--}}
                        <option value="company_name">公司名称</option>
                        <option value="company_linkman">联系人</option>
                        <option value="company_mobile">手机号</option>
                    </select>
                    <input type="text"   name="keyWord" value=""  placeholder="请输入关键字"   />
                    <button class="btn btn-normal search_frm">搜索</button>
                </div>
            </form>

        </div>

        <table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>公司名称</th>
				<th>开通模块编</th>
                <th>接线部门</th>
                <th>开启状态</th>
                <th>联系人</th>
                <th>公司状态<hr/>帐号来源类型</th>
                <th>到期时间</th>
                <th>后台地址<hr/>h5地址</th>
				<th width=200>操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			</tbody>
		</table>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        var AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
        var LIST_FUNCTION_NAME = "reset_list_self";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
        var AJAX_URL = "{{ url('api/admin/company/ajax_alist') }}";//ajax请求的url
        var ADD_URL = "{{ url('admin/company/add/0') }}"; //添加url
        var SHOW_URL = "{{url('company/info/')}}/";//显示页面地址前缀 + id
        var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
        var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
        var EDIT_URL = "{{url('admin/company/add/')}}/";//修改页面地址前缀 + id
        var DEL_URL = "{{ url('api/admin/company/ajax_del') }}";//删除页面地址
        var BATCH_DEL_URL = "{{ url('api/manage/company/ajax_del') }}";//批量删除页面地址
        var EXPORT_EXCEL_URL = "{{ url('manage/company/export') }}";//导出EXCEL地址
        var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/company/import_template') }}";//导入EXCEL模版地址
        var IMPORT_EXCEL_URL = "{{ url('api/manage/company/import') }}";//导入EXCEL地址
        var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>
	<script src="{{ asset('/js/admin/lanmu/company.js') }}?10"  type="text/javascript"></script>
@endpush
