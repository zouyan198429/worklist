@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 工单管理</div>
	<div class="mm">
		@include('common.pageParams')
		<form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
		<div class="mmhead" id="mywork">
			<div class="tabbox" >
				<a href="javascript:void(0);" data-status="" class="status_click">全部工单</a>
                @foreach ($status as $k=>$txt)
                    <a href="javascript:void(0);" data-status="{{ $k }}" class="status_click @if ($k == 1) on @endif">{{ $txt }}</a>
                @endforeach
			</div>
			<div class="msearch fr">
                <select style="width:80px; height:28px; display:none;" name="status" >
                    <option value="">全部</option>
                    @foreach ($status as $k=>$txt)
                        <option value="{{ $k }}" @if ($k == 1) selected @endif >{{ $txt }}</option>
                    @endforeach
                </select>
				<select style="width:80px; height:28px;"  name="field">
					<option value="call_number">手机号</option>
					<option value="customer_name">姓名</option>
					<option value="work_num">工单号</option>
				</select> <input type="text" value=""  name="keyWord" /> <button class="btn btn-normal  search_frm ">搜索</button>
			</div>
		</div>
		</form>
		{{--
		<div class="table-header">
			<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>
			<button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出EXCEL</button>
			<button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入EXCEL</button>
		</div>
		--}}
		<table  id="dynamic-table"  class="table2">
			<thead>
			<tr>
				<th>工单号<br/>来电号码<br/>联系电话</th>
				<th>工单来源</th>
				<th>工单类型</th>
				<th width="350px">工单内容</th>
				<th>客户位置</th>
				<th>下单时间</th>
				<th>工单等级</th>
				<th>派单人员</th>
				<th>区县客服</th>
				<th>状态</th>
				<!-- 
				<th>客户姓名</th>
				<th>客户类别</th> -->
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody  id="data_list">
			{{---
			<tr>
				<td>345236864</td>
				<td>05-22  15:33</td>
				<td>张芸</td>
				<td>雷栋栋</td>
				<td>2小时</td>
				<td>剩余1小时12分</td>
				<td><a href="tel:15366658554" class="btn" >15366658554 <i class="fa fa-phone-square fa-fw" aria-hidden="true"></i> </a></td>
				<td>王(女)</td>
				<td>企业</td>
				<td>秦州区/中城街道</td>
				<td><a href="" class="btn btn-gray" >查看</a></td>
			</tr> 
			--}}
			</tbody>
		</table>
		<div class="mmfoot">
			<div class="mmfleft"></div>
			<div class="pagination">
				{{--
				<a href="" class="on" > - </a>
				<a href="" > 1 </a>
				<a href=""> 2 </a>
				<a href=""> 4 </a>
				<a href=""> 5 </a>
				<a href=""> > </a>
				--}}
			</div>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
        const AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
        const AJAX_URL = "{{ url('api/manage/work/ajax_alist') }}";//ajax请求的url
        const ADD_URL = "{{ url('manage/work/add/0') }}"; //添加url
        const SHOW_URL = "{{url('manage/work/info/')}}/";//显示页面地址前缀 + id
        const SHOW_URL_TITLE = "工单详情" ;// 详情弹窗显示提示
        const EDIT_URL = "{{url('manage/work/add/')}}/";//修改页面地址前缀 + id
        const DEL_URL = "{{ url('api/manage/work/ajax_del') }}";//删除页面地址
        const BATCH_DEL_URL = "{{ url('api/manage/work/ajax_del') }}";//批量删除页面地址
        const EXPORT_EXCEL_URL = "{{ url('manage/work/add/0') }}"; //"{{ url('api/manage/work/export') }}";//导出EXCEL地址
        const IMPORT_EXCEL_URL = "{{ url('manage/work/add/0') }}"; //"{{ url('api/manage/work/import') }}";//导入EXCEL地址

		const SATUS_COUNT_URL = "{{ url('api/manage/work/ajax_status_count') }}";// ajax工单状态统计 url

	</script>
	<script src="{{asset('js/common/list.js')}}"></script>

    <script type="text/javascript">

        var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            $('.search_frm').trigger("click");// 触发搜索事件
            ajax_status_count(0, 0);//ajax工单状态统计
            reset_list(false);
        });

        //ajax工单状态统计
        function ajax_status_count(staff_id, operate_staff_id){
            if (!SUBMIT_FORM) return false;//false，则返回

            // 验证通过
            SUBMIT_FORM = false;//标记为已经提交过
            var data = {
                'staff_id': staff_id,
                'operate_staff_id': operate_staff_id,
            };
            console.log(SATUS_COUNT_URL);
            console.log(data);
            var layer_count_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : SATUS_COUNT_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log(ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var statusCount = ret.result;
                        console.log(statusCount);
                    }
                    SUBMIT_FORM = true;//标记为未提交过
                    layer.close(layer_count_index)//手动关闭
                }
            });
            return false;
        }
    </script>
	<script src="{{ asset('js/manage/lanmu/work.js') }}"  type="text/javascript"></script>
@endpush