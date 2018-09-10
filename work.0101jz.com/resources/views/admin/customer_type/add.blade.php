@extends('layouts.admin')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 添加客户分类</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>名称<span class="must">*</span></th>
				<td>
					<input type="text" class="inp wnormal" name="type_name" value="{{ $type_name or '' }}" placeholder="分类名称" placeholder=" " autofocus  required />
				</td>
			</tr>
			<tr>
				<th>排序[降序]</th>
				<td>
					<input type="number" class="inp wnormal"  name="sort_num" onkeyup="isnum(this) " onafterpaste="isnum(this)"  value="{{ $sort_num or '' }}"   placeholder="请输入整数" autofocus  required />
				</td>
			</tr>
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
        const SAVE_URL = "{{ url('api/admin/customer_type/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('admin/customer_type/index')}}";//保存成功后跳转到的地址


        var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            //提交
            $(document).on("click","#submitBtn",function(){
                //var index_query = layer.confirm('您确定提交保存吗？', {
                //    btn: ['确定','取消'] //按钮
                //}, function(){
                ajax_form();
                //    layer.close(index_query);
                // }, function(){
                //});
                return false;
            })

        });

        //ajax提交表单
        function ajax_form(){
            if (!SUBMIT_FORM) return false;//false，则返回

            // 验证信息
            var id = $('input[name=id]').val();
            if(!judge_validate(4,'记录id',id,true,'digit','','')){
                return false;
            }

            var type_name = $('input[name=type_name]').val();
            if(!judge_validate(4,'名称',type_name,true,'length',2,40)){
                return false;
            }

            var sort_num = $('input[name=sort_num]').val();
            if(!judge_validate(4,'排序',sort_num,false,'digit','','')){
                return false;
            }

            // 验证通过
            SUBMIT_FORM = false;//标记为已经提交过
            var data = $("#addForm").serialize();
            console.log(SAVE_URL);
            console.log(data);
            var layer_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : SAVE_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log(ret);
                    if(!ret.apistatus){//失败
                        SUBMIT_FORM = true;//标记为未提交过
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        go(LIST_URL);
                        // var supplier_id = ret.result['supplier_id'];
                        //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                        //    SUPPLIER_ID_VAL = supplier_id;
                        //    $('input[name="supplier_id"]').val(supplier_id);
                        //}
                        // save_success();
                    }
                    layer.close(layer_index)//手动关闭
                }
            })
            return false;
        }
	</script>
	{{--<script src="{{ asset('/js/lanmu/inputcls_edit.js') }}"  type="text/javascript"></script>--}}
@endpush