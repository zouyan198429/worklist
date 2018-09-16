@extends('layouts.weixiualert')

@push('headscripts')
	{{--  本页单独使用 --}}
@endpush

@section('content')
	{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 反馈回复</div>--}}
	<div class="mm">
		@include('weixiu.work.publicinfo')
		<form class="am-form am-form-horizontal" method="post"  id="addForm">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
			<table class="table1">
				<tr>
					<th>内容<span class="must">*</span></th>
					<td>
						<textarea type="text" class="inptext wlong" name="win_content" />{{ $win_content or '' }}</textarea>
						<p class="tip"></p>
					</td>
				</tr>

			</table>
			<div class="line"> </div>
			<table class="table1">
				<tr>
					<th> </th>
					<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
				</tr>
			</table>
			<div class="line"> </div>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')

	<script type="text/javascript">
        const SAVE_URL = "{{ url('api/weixiu/work/ajax_win') }}";// ajax保存记录地址
        // const LIST_URL = "{{url('weixiu/work')}}";//保存成功后跳转到的地址


        //获取当前窗口索引
        var PARENT_LAYER_INDEX = parent.layer.getFrameIndex(window.name);
        //让层自适应iframe
        parent.layer.iframeAuto(PARENT_LAYER_INDEX);
        // parent.layer.full(PARENT_LAYER_INDEX);
        //关闭iframe
        $(document).on("click",".closeIframe",function(){
            iframeclose(PARENT_LAYER_INDEX);
        });
        //刷新父窗口列表
        function parent_only_reset_list(){
            window.parent.reset_list();//刷新父窗口列表
        }
        //关闭弹窗,并刷新父窗口列表
        function parent_reset_list_iframe_close(){
            window.parent.reset_list();//刷新父窗口列表
            parent.layer.close(PARENT_LAYER_INDEX);
        }
        //关闭弹窗
        function parent_reset_list(){
            parent.layer.close(PARENT_LAYER_INDEX);
        }


        var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            //提交
            $(document).on("click","#submitBtn",function(){
                var index_query = layer.confirm('您确定提交吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                     ajax_form();
                    layer.close(index_query);
                }, function(){
                });

                return false;
            })

        });

        // //ajax提交表单
        function ajax_form(){
            if (!SUBMIT_FORM) return false;//false，则返回

            // 验证信息
            var id = $('input[name=id]').val();
            if(!judge_validate(4,'记录id',id,true,'digit','','')){
                return false;
            }

            var win_content = $('textarea[name=win_content]').val();
            if(!judge_validate(4,'结单内容',win_content,true,'length',2,2000)){
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
                        // go(LIST_URL);
                        parent_reset_list_iframe_close();
                        // var supplier_id = ret.result['supplier_id'];
                        //if(SUPPLIER_ID_VAL <= 0 && judge_integerpositive(supplier_id)){
                        //    SUPPLIER_ID_VAL = supplier_id;
                        //    $('input[name="supplier_id"]').val(supplier_id);
                        //}
                        // save_success();
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
            return false;
        }
	</script>
	{{--<script src="{{ asset('js/huawu/lanmu/work_win.js') }}"  type="text/javascript"></script>--}}
@endpush