@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
<style type="text/css">
	.right { color: green;font-weight: bold;}
	.wrong {color: red;font-weight: bold;}
    .pink {color: #DB348A;font-weight: bold;}
</style>
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}试卷</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm" onsubmit="return false;">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>试卷名称</th>
				<td>
					<input type="text" class="inp wlong" name="paper_name" value="{{ $paper_name or '' }}" placeholder="" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>试题顺序</th>
				<td>
					@foreach ($selTypes as $k=>$txt)
					<label>
						<input type="radio" name="subject_order_type" value="{{ $k }}"  @if(isset($subject_order_type) && $subject_order_type == $k) checked="checked"  @endif/>{{ $txt }}
					</label>
					@endforeach
				</td>
			</tr>
			<tr>
				<th>试题</th>
				<td>
					<span class="subject_amount">{{ $subject_amount or '0' }}</span> 题
				</td>
			</tr>
			<tr>
				<th>总分</th>
				<td>
					<span class="total_score">{{ $total_score or '0' }}</span> 分
				</td>
			</tr>

			<tr>
				<td colspan="2" class="subject_td">
					@foreach ($subjectTypes as $type)
					<div style="margin-top: 20px;" class="subject_types  subject_type_{{ $type['type_id'] or 0 }}">
						<input type="hidden" name="type_id[]" value="{{ $type['type_id'] or 0 }}"/>
                        <input type="hidden" name="type_name[]" value="{{ $type['type_name'] or 0 }}"/>
						<div class="table-header">
							<div>
								{{ $type['type_name'] or '' }} ：共
                                <input type="hidden" name="subject_count[]"  value="{{ $type['subject_count'] or '0' }}" style="width:40px;"  onkeyup="isnum(this) " onafterpaste="isnum(this)" />
                                <span class="subject_count">{{ $type['subject_count'] or '0' }}</span>
                                题，

                                总分 <input type="text" name="subject_score[]"  value="{{ $type['subject_score'] or '0' }}" style="width:50px;"  onkeyup="numxs(this) " onafterpaste="numxs(this)" /> 分
								{{--<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="action.batchDel(this)">随机生成试题</button>--}}
								<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.select(this,{{ $type['type_id'] or 0 }})">选择试题</button>
							</div>
							<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="otheraction.batchDel(this, '.subject_types', 'tr')">批量删除</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-up bigger-60"  onclick="otheraction.moveUp(this, '.subject_types')" >上移</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-down bigger-60"  onclick="otheraction.moveDown(this, '.subject_types')" >下移</button>
						</div>
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 90px;">
									<label class="pos-rel">
										<input type="checkbox" class="ace check_all" value="" onclick="otheraction.seledAll(this,'.table2')">
										<span class="lbl">全选</span>
									</label>

								</th>
								<th >试题  </th>
								<th>答案</th>
								<th>正确答案</th>
								<th>分类</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody class="data_list" >
                            {{--
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
                                    <input type="hidden" name="subject_ids[]" value="1"/>
                                    <input type="hidden" name="subject_history_ids[]" value="1"/>
								</td>
								<td>
									1、我是邹燕吗?aaaaaa
								</td>
								<td align="center">
									A、1+1   <span class="wrong">×</span><br>
									B、32+40   <span class="right">√</span><br>
									C、22+10   <span class="right">√</span><br>
									D、50+40   <span class="right">√</span>
								</td>
								<td>
									B、C、D
								</td>
								<td>
									营销知识
								</td>
								<td>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this, 'tr')">
										<i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this, 'tr')">
										<i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.edit(1)">
										<i class="ace-icon fa fa-pencil bigger-60"> 编辑</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
										<i class="ace-icon fa fa-trash-o bigger-60"> 移除</i>
									</a>
								</td>
							</tr>
							--}}
						</table>
					</div>
					@endforeach
				</td>
			</tr>
			<tr>
				<th> </th>
				<td><button class="btn btn-l wnormal"   id="submitBtn">提交</button></td>
			</tr>

		</table>
		</form>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/manage/paper/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('manage/paper')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_SUBJECT_URL = "{{ url('api/manage/paper/ajax_get_subject') }}";// ajax初始化试题地址
        var AJAX_UPDATE_SUBJECT_URL = "{{ url('api/manage/paper/ajax_update_subject') }}";// ajax更新试题地址
        var AJAX_SUBJECT_ADD_URL = "{{ url('api/manage/paper/ajax_add_subject') }}";// ajax添加试题地址
        var SELECT_SUBJECT_URL = "{{ url('manage/subject/select') }}";// 选择试题地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class

        {{--var ANSWER_LIST = @json($answer_list) ;--}}
        {{--var ANSWER_DATA_LIST = {--}}
            {{--'data_list':ANSWER_LIST--}}
        {{--};--}}
        {{--var DEFAULT_DATA_LIST = [{'id':0, 'answer_content':'', 'is_right':0,}];// 默认答案--}}
	</script>
	{{--<script src="{{ asset('/js/manage/lanmu/paper_edit.js') }}"  type="text/javascript"></script>--}}

	<script type="text/javascript">

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
            });
            // 题数量改变
            $(document).on("change",'input[name="subject_count[]"]',function(){
                // var subject_count = $(this).val();
                getCount();
                return false;
            });
            // 总分改变
            $(document).on("change",'input[name="subject_score[]"]',function(){
                // var subject_score = $(this).val();
                getScore();
                return false;
            });
            initSubject(ID_VAL);// 页面初始化试题
        });

        // 总题数
		function  getCount() {
            var total_count = 0;
            $('.subject_td').find('input[name="subject_count[]"]').each(function () {
                var subject_count = $(this).val();
                total_count += parseInt(subject_count);
            });
            $('.subject_amount').html(total_count);
        }
        // 总分数
		function getScore() {
            var total_score = 0;
            $('.subject_td').find('input[name="subject_score[]"]').each(function () {
                var subject_score = $(this).val();
                total_score += parseFloat(subject_score);
            });
            $('.total_score').html(total_score);
        }
        //ajax提交表单
        function ajax_form(){
            if (!SUBMIT_FORM) return false;//false，则返回

            // 验证信息
            var id = $('input[name=id]').val();
            if(!judge_validate(4,'记录id',id,true,'digit','','')){
                return false;
            }

            // 试卷名称
            var paper_name = $('input[name=paper_name]').val();
            if(!judge_validate(4,'试卷名称',paper_name,true,'length',2,150)){
                return false;
            }

            var subject_order_type = $('input[name=subject_order_type]:checked').val() || '';
            var judge_seled = judge_validate(1,'试题顺序',subject_order_type,true,'custom',/^[01]$/,"");
            if(judge_seled != ''){
                layer_alert("请选择试题顺序",3,0);
                //err_alert('<font color="#000000">' + judge_seled + '</font>');
                return false;
            }

            // 判断试题
            var subject_count = 0;
            var blockObj = $('.subject_td').find('.subject_types');
            if(blockObj.length <= 0){
                layer_alert("请先增加试题类型",3,0);
                return false;
            }
            var is_err = false;
            blockObj.each(function(){
                var temObj = $(this);
                // 试题类型名称
                var subject_type_name = temObj.find('input[name="type_name[]"]').val();
                // 试题数量
                var tem_subject_count = temObj.find('input[name="subject_count[]"]').val();
                // 分数
                var tem_subject_score = temObj.find('input[name="subject_score[]"]').val();
                if(parseFloat(tem_subject_score) > 0 && parseInt(tem_subject_count) <= 0) {
                    layer_alert("" + subject_type_name + "题共0题时，分数不能 > 0 !",3,0);
                    is_err = true;
                    return false;
                }

                if(parseFloat(tem_subject_score) <= 0 && parseInt(tem_subject_count) > 0) {
                    layer_alert("" + subject_type_name + "题，分数不能 <= 0 !",3,0);
                    is_err = true;
                    return false;
                }
                subject_count += parseInt(tem_subject_count);
            });
            if(is_err){
                return false;
            }
            // 没有选择答案
            if(parseInt(subject_count) <= 0){
                layer_alert("请选择试题!",3,0);
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
            });
            return false;
        };

        //业务逻辑部分
        var otheraction = {
            // add : function(){// 增加答案
            //     var data_list = {
            //         'data_list' : DEFAULT_DATA_LIST
            //     };
            //     // initAnswer(data_list, 2);// 初始化答案列表
            //     return false;
            // },
            select: function(obj, subject_type){// 更新
                var recordObj = $(obj);
                //获得表单各name的值
                var data = {};// parent.get_frm_values(SURE_FRM_IDS);// {}
                data['subject_type'] = subject_type;
                console.log(data);
                var url_params = parent.get_url_param(data);
                var weburl = SELECT_SUBJECT_URL + '?' + url_params;
                console.log(weburl);
                // go(SHOW_URL + id);
                // location.href='/pms/Supplier/show?supplier_id='+id;
                // var weburl = SHOW_URL + id;
                // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
                var tishi = '选择试题';//"查看供应商";
                layeriframe(weburl,tishi,950,600,0);
                return false;
            },
            edit : function(obj, parentTag, subject_id){// 更新
                var recordObj = $(obj);
                var index_query = layer.confirm('确定更新当前记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var trObj = recordObj.closest(parentTag);// 'tr'
                    updateSubject(subject_id,trObj);
                    layer.close(index_query);
                }, function(){
                });
                return false;
            },
            del : function(obj, parentTag){// 删除
                var recordObj = $(obj);
                var index_query = layer.confirm('确定移除当前记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var trObj = recordObj.closest(parentTag);// 'tr'
                    trObj.remove();
                    autoCountSubjectNum();
                    layer.close(index_query);
                }, function(){
                });
                return false;
            },
            batchDel:function(obj, parentTag, delTag) {// 批量删除
                var recordObj = $(obj);
                var index_query = layer.confirm('确定移除选中记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var hasDel = false;
                    recordObj.closest(parentTag).find('.check_item').each(function () {
                        if (!$(this).prop('disabled') && $(this).val() != '' &&  $(this).prop('checked') ) {
                            // $(this).prop('checked', checkAllObj.prop('checked'));
                            var trObj = $(this).closest(delTag);// 'tr'
                            trObj.remove();
                            hasDel = true;
                        }
                    });
                    if(!hasDel){
                        err_alert('请选择需要操作的数据');
					}
                    autoCountSubjectNum();
                    layer.close(index_query);
                }, function(){
                });
                return false;
            },
            moveUp : function(obj, parentTag){// 上移
                var recordObj = $(obj);
                var current = recordObj.closest(parentTag);//获取当前<tr>  'tr'
                var prev = current.prev();  //获取当前<tr>前一个元素
                console.log('index', current.index());
                if (current.index() > 0) {
                    current.insertBefore(prev); //插入到当前<tr>前一个元素前
                }else{
                    layer_alert("已经是第一个，不能移动了。",3,0);
                }
                return false;
            },
            moveDown : function(obj, parentTag){// 下移
                var recordObj = $(obj);
                var current = recordObj.closest(parentTag);//获取当前<tr>'tr'
                var next = current.next(); //获取当前<tr>后面一个元素
                console.log('length', next.length);
                console.log('next', next);
                if (next.length > 0 && next) {
                    current.insertAfter(next);  //插入到当前<tr>后面一个元素后面
                }else{
                    layer_alert("已经是最后一个，不能移动了。",3,0);
                }
                return false;
            },
            seledAll:function(obj, parentTag){
                var checkAllObj =  $(obj);
                /*
                checkAllObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function(){
                    if(!$(this).prop('disabled')){
                        $(this).prop('checked', checkAllObj.prop('checked'));
                    }
                });
                */
                checkAllObj.closest(parentTag).find('.check_item').each(function(){
                    if(!$(this).prop('disabled')){
                        $(this).prop('checked', checkAllObj.prop('checked'));
                    }
                });
            },
            seledSingle:function(obj, parentTag) {// 单选点击
                var checkObj = $(obj);
                var allChecked = true;
                /*
                 checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
                    if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                        // $(this).prop('checked', checkAllObj.prop('checked'));
                        allChecked = false;
                        return false;
                    }
                });
                */
                checkObj.closest(parentTag).find('.check_item').each(function () {
                    if (!$(this).prop('disabled') && $(this).val() != '' &&  !$(this).prop('checked') ) {
                        // $(this).prop('checked', checkAllObj.prop('checked'));
                        allChecked = false;
                        return false;
                    }
                });
                // 全选复选操选中/取消选中
                /*
                checkObj.closest('#' + DYNAMIC_TABLE).find('input:checkbox').each(function () {
                    if (!$(this).prop('disabled') && $(this).val() == ''  ) {
                        $(this).prop('checked', allChecked);
                        return false;
                    }
                });
                */
                checkObj.closest(parentTag).find('.check_all').each(function () {
                    $(this).prop('checked', allChecked);
                });

            },
        };

        // 页面初始化试题
        function initSubject(id) {
            if(id <= 0) return ;
            var data = {};
            data['id'] = id;
            var layer_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : AJAX_SUBJECT_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var subject_list = ret.result.subject_list;
                        console.log('subject_list', subject_list);
                        // 循环遍历
                        for(var subject_k in subject_list) {//遍历json对象的每个key/value对,p为key
                            var data_list = {
                                'data_list': subject_list[subject_k],
                            };
                            // 解析数据
                            initAnswer(subject_k, data_list, 1);
                        }
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
        }

        // 更新试题
        // id 试题id
        // trObj tr对象
        function updateSubject(id, trObj) {
            if(id <= 0) return ;
            var data = {};
            data['id'] = id;
            var layer_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : AJAX_UPDATE_SUBJECT_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var subject_list = ret.result;
                        console.log('subject_list', subject_list);
                        var data_list = {
                            'data_list': subject_list,
                        };
                        // 解析数据
                        var htmlStr = initAnswer('', data_list, 3);
                        trObj.after(htmlStr);
                        trObj.remove();
                        autoCountSubjectNum();
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
        }

        // 初始化答案列表
        // data_list 数据对象 {'data_list':[{}]}
        // type类型 1 全替换 2 追加到后面 3 返回html
        function initAnswer(class_name, data_list, type){
            var htmlStr = resolve_baidu_template(DYNAMIC_BAIDU_TEMPLATE,data_list,'');//解析
            if(type == 3) return htmlStr;
            //alert(htmlStr);
            //alert(body_data_id);
            if(type == 1){
                $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).html(htmlStr);
            }else if(type == 2){
                $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).append(htmlStr);
            }
        }

        // 获得试题数量
        function autoCountSubjectNum(){
            var total = 0;
            $('.subject_td').find('.subject_types').each(function () {
                var subjectTypeObj = $(this);
                var subject_num = subjectTypeObj.find('.data_list').find('tr').length;
                console.log('subject_num',subject_num);
                subjectTypeObj.find('input[name="subject_count[]"]').val(subject_num);
                subjectTypeObj.find('.subject_count').html(subject_num);
                total += parseInt(subject_num);
            });
            $('.subject_amount').html(total);

        }

        // 获得选中的试题id 数组
        // subject_type 试题类型id
        function getSelectedSubjectIds(subject_type){
            var subject_ids = [];
            $('.subject_td').find('.subject_type_' + subject_type).find('.data_list').find('input[name="subject_ids[]"]').each(function () {
                var subject_id = $(this).val();
                subject_ids.push(subject_id);
            });
            console.log('subject_ids' , subject_ids);
            return subject_ids;
        }

        // 取消
        // subject_type 类型id
        // subject_id 试题id
        function removeSubject(subject_type, subject_id){
            $('.subject_td').find('.subject_type_' + subject_type).find('.data_list').find('input[name="subject_ids[]"]').each(function () {

                var tem_subject_id = $(this).val();
                if(subject_id == tem_subject_id){
                    $(this).closest('tr').remove();
                    return ;
                }
            });
            autoCountSubjectNum();
        }

        // 增加
        // subject_type 类型id
        // subject_id 试题id, 多个用,号分隔
        function addSubject(subject_type, subject_id){
            if(subject_id == '') return ;
            var data = {};
            data['id'] = subject_id;
            var layer_index = layer.load();
            $.ajax({
                'async': false,// true,//false:同步;true:异步
                'type' : 'POST',
                'url' : AJAX_SUBJECT_ADD_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var subject_list = ret.result;
                        console.log('subject_list', subject_list);
                        var data_list = {
                            'data_list': subject_list,
                        };
                        // 解析数据
                        initAnswer('subject_type_' + subject_type, data_list, 2);
                        autoCountSubjectNum();
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
        }

	</script>
    <!-- 前端模板部分 -->
    <!-- 列表模板部分 开始  <! -- 模板中可以用HTML注释 -- >  或  <%* 这是模板自带注释格式 *%>-->
    <script type="text/template"  id="baidu_template_data_list">
        <%for(var i = 0; i<data_list.length;i++){
        var item = data_list[i];
        var now_subject = item.now_subject;
        can_modify = true;
        %>
        <tr>
            <td>
                <label class="pos-rel">
                    <input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="<%=item.subject_id%>">
                    <span class="lbl"></span>
                </label>
                <input type="hidden" name="subject_ids[]" value="<%=item.subject_id%>"/>
                <input type="hidden" name="subject_history_ids[]" value="<%=item.subject_history_id%>"/>
            </td>
            <td><%=item.title%></td>
            <td align="center"><%=item.answer_txt%></td>
            <td><%=item.answer_right%></td>
            <td><%=item.type_name%></td>
            <td>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this, 'tr')">
                    <i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
                </a>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this, 'tr')">
                    <i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
                </a>
                <%if( now_subject == 2 || now_subject == 4){%>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.edit(this, 'tr', <%=item.subject_id%>)">
                    <i class="ace-icon fa fa-pencil bigger-60 pink"> 更新[试题库已更新]</i>
                </a>
                <%}%>
                <%if( now_subject == 1){%>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
                    <i class="ace-icon fa fa-trash-o bigger-60 wrong"> 删除[试题库已删]</i>
                </a>
                <%}%>
                <a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
                    <i class="ace-icon fa fa-trash-o bigger-60" style=""> 移除</i>
                </a>
            </td>
        </tr>
        <%}%>
    </script>
    <!-- 列表模板部分 结束-->
    <!-- 前端模板结束 -->
@endpush