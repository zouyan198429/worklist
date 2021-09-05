@extends('layouts.manage')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}考试</div>
	<div class="mm">
		<form class="am-form am-form-horizontal" method="post"  id="addForm" onsubmit="return false;">
			<input type="hidden" name="id" value="{{ $id or 0 }}"/>
		<table class="table1">
			<tr>
				<th>场次</th>
				<td>
					<input type="text" class="inp wlong" name="exam_num" value="{{ $exam_num or '' }}" placeholder="请输入场次" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考试主题</th>
				<td>
					<input type="text" class="inp wlong" name="exam_subject" value="{{ $exam_subject or '' }}" placeholder="请输入考试主题" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>开考时间</th>
				<td>
					<input type="text" class="inp wlong" name="exam_begin_time" value="{{ $exam_begin_time or '' }}" placeholder="请选择开考时间" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考试时长</th>
				<td>
					<input type="text" class="inp wlong" name="exam_minute" value="{{ $exam_minute or '' }}" placeholder="请输入考试时长" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>选择试卷</th>
				<td>
					<span class="paper_name">{{ $paper_name or '' }}</span>
					<input type="hidden" name="paper_id"  value="{{ $paper_id or '' }}" />
					<input type="hidden" name="paper_history_id"  value="{{ $paper_history_id or '' }}" />
					<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectPaper(this)">选择试卷</button>

					<button class="btn btn-danger  btn-xs ace-icon fa fa-pencil bigger-60 update_paper" @if(isset($now_paper) && in_array($now_paper,[0,1])) style="display: none;"  @endif  onclick="otheraction.updatePaper(this)">更新[当前试卷已更新]</button>

				</td>
			</tr>
			<tr>
				<th>及格分数</th>
				<td>
					<input type="text" class="inp wlong" name="pass_score" value="{{ $pass_score or '' }}" placeholder="请输入及格分数" autofocus  required />

				</td>
			</tr>
			<tr>
				<th>参与人员</th>
				<td>
					共 <span class="subject_num">{{ $subject_num or '' }}</span> 人
					<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="action.batchDel(this)">选择人员</button>
				</td>
			</tr>
			<tr>
				<td colspan="2"  class="staff_td">
                    <?php for($i=1;$i<=3;$i++) { ?>
					<div style="margin-top: 20px;" class="staffs  department_group_{{ $type['type_id'] or 0 }}">
						<div class="table-header">
							<div>
								<span class="department_name">甘谷县</span>/<span class="group_name">新兴片区{{ $i or '' }}</span> ：
								<input type="hidden" name="department_id[]"  value="" />
								<input type="hidden" name="department_name[]"  value="" />
								<input type="hidden" name="group_id[]"  value="" />
								<input type="hidden" name="group_name[]"  value="" />
								共
								<input type="hidden" name="staff_num[]"  value="" style="width:40px;"  onkeyup="isnum(this) " onafterpaste="isnum(this)" />
								<span class="staff_num">10</span>
								题
								<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-square bigger-60"  onclick="otheraction.select(this,{{ $type['type_id'] or 0 }})">选择人员</button>
							</div>
							<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="otheraction.batchDel(this, '.staffs', 'tr')">批量删除</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-up bigger-60"  onclick="otheraction.moveUp(this, '.staffs')" >上移</button>
							<button class="btn btn-success  btn-xs ace-icon fa fa-arrow-down bigger-60"  onclick="otheraction.moveDown(this, '.staffs')" >下移</button>
						</div>
						<table class="table2">
							<thead>
							<tr>
								<th style="width: 90px;">
									<label class="pos-rel">
										<input type="checkbox" class="ace check_all" value="" onclick="otheraction.seledAll(this,'.table2')">
										<span class="lbl">全选</span>
									</label>
									<input type="hidden" name="subject_ids[]" value="1502"/>
            						<input type="hidden" name="subject_history_ids[]" value="17"/>
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
							<tbody class="data_list">
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
								</td>
								<td>38000592</td>
								<td>甘谷县/新兴片区aaa</td>
								<td>孙昌义</td>
								<td>未知</td>
								<td>营业员</td>
								<td>15025978154</td>
								<td>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this, 'tr')">
										<i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this, 'tr')">
										<i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.edit(this, 'tr', 10)">
										<i class="ace-icon fa fa-pencil bigger-60 pink"> 更新[员工已更新]</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
										<i class="ace-icon fa fa-trash-o bigger-60 wrong"> 删除[员工已删]</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
										<i class="ace-icon fa fa-trash-o bigger-60"> 移除</i>
									</a>
								</td>
							</tr>
							<tr>
								<td>
									<label class="pos-rel">
										<input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="12">
										<span class="lbl"></span>
									</label>
								</td>
								<td>38000592</td>
								<td>甘谷县/新兴片区bbb</td>
								<td>孙昌义</td>
								<td>未知</td>
								<td>营业员</td>
								<td>15025978154</td>
								<td>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveUp(this, 'tr')">
										<i class="ace-icon fa fa-arrow-up bigger-60"> 上移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.moveDown(this, 'tr')">
										<i class="ace-icon fa fa-arrow-down bigger-60"> 下移</i>
									</a>
									<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
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
        var SAVE_URL = "{{ url('api/manage/exam/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('manage/exam')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_STAFF_URL = "{{ url('api/manage/exam/ajax_get_staff') }}";// ajax初始化参考人员地址
        var AJAX_UPDATE_STAFF_URL = "{{ url('api/manage/exam/ajax_update_staff') }}";// ajax更新参考人员地址
        var AJAX_STAFF_ADD_URL = "{{ url('api/manage/exam/ajax_add_staff') }}";// ajax添加参考人员地址
        var SELECT_STAFF_URL = "{{ url('manage/staff/select') }}";// 选择参考人员地址
        var SELECT_PAPER_URL = "{{ url('manage/paper/select') }}";// 选择试卷地址
		var AJAX_PAPER_ADD_URL = "{{ url('api/manage/exam/ajax_add_paper') }}";// ajax添加/修改试卷地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class
	</script>
	{{--<script src="{{ asset('/js/manage/lanmu/exam_edit.js') }}"  type="text/javascript"></script>--}}
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
            // $(document).on("change",'input[name="staff_num[]"]',function(){
            //     // var staff_num = $(this).val();
            //     getCount();
            //     return false;
            // });
            // 总分改变
            // $(document).on("change",'input[name="subject_score[]"]',function(){
            //     // var subject_score = $(this).val();
            //     getScore();
            //     return false;
            // });
            // initSubject(ID_VAL);// 页面初始化试题
        });
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
            var staff_num = 0;
            var blockObj = $('.staff_td').find('.staffs');
            if(blockObj.length <= 0){
                layer_alert("请先增加试题类型",3,0);
                return false;
            }
            var is_err = false;
            blockObj.each(function(){
                var temObj = $(this);
                // 试题类型名称
                var department_group_name = temObj.find('input[name="type_name[]"]').val();
                // 试题数量
                var tem_staff_num = temObj.find('input[name="staff_num[]"]').val();
                // 分数
                var tem_subject_score = temObj.find('input[name="subject_score[]"]').val();
                if(parseFloat(tem_subject_score) > 0 && parseInt(tem_staff_num) <= 0) {
                    layer_alert("" + department_group_name + "题共0题时，分数不能 > 0 !",3,0);
                    is_err = true;
                    return false;
                }

                if(parseFloat(tem_subject_score) <= 0 && parseInt(tem_staff_num) > 0) {
                    layer_alert("" + department_group_name + "题，分数不能 <= 0 !",3,0);
                    is_err = true;
                    return false;
                }
                staff_num += parseInt(tem_staff_num);
            });
            if(is_err){
                return false;
            }
            // 没有选择答案
            if(parseInt(staff_num) <= 0){
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
            selectPaper: function(obj){// 更新
                var recordObj = $(obj);
                //获得表单各name的值
                var weburl = SELECT_PAPER_URL;
                console.log(weburl);
                // go(SHOW_URL + id);
                // location.href='/pms/Supplier/show?supplier_id='+id;
                // var weburl = SHOW_URL + id;
                // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
                var tishi = '选择试卷';//"查看供应商";
                layeriframe(weburl,tishi,950,600,0);
                return false;
            },
            updatePaper : function(obj){// 试卷更新
                var recordObj = $(obj);
                var index_query = layer.confirm('确定更新当前记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var paper_id = $('input[name=paper_id]').val();
                    addPaper(paper_id);
                    layer.close(index_query);
                }, function(){
                });
                return false;
            },
            // add : function(){// 增加答案
            //     var data_list = {
            //         'data_list' : DEFAULT_DATA_LIST
            //     };
            //     // initAnswer(data_list, 2);// 初始化答案列表
            //     return false;
            // },
            // select: function(obj, subject_type){// 更新
            //     var recordObj = $(obj);
            //     //获得表单各name的值
            //     var data = {};// parent.get_frm_values(SURE_FRM_IDS);// {}
            //     data['subject_type'] = subject_type;
            //     console.log(data);
            //     var url_params = parent.get_url_param(data);
            //     var weburl = SELECT_SUBJECT_URL + '?' + url_params;
            //     console.log(weburl);
            //     // go(SHOW_URL + id);
            //     // location.href='/pms/Supplier/show?supplier_id='+id;
            //     // var weburl = SHOW_URL + id;
            //     // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
            //     var tishi = '选择试题';//"查看供应商";
            //     layeriframe(weburl,tishi,950,600,0);
            //     return false;
            // },
            // edit : function(obj, parentTag, subject_id){// 更新
            //     var recordObj = $(obj);
            //     var index_query = layer.confirm('确定更新当前记录？', {
            //         btn: ['确定','取消'] //按钮
            //     }, function(){
            //         var trObj = recordObj.closest(parentTag);// 'tr'
            //         updateSubject(subject_id,trObj);
            //         layer.close(index_query);
            //     }, function(){
            //     });
            //     return false;
            // },
            del : function(obj, parentTag){// 删除
                var recordObj = $(obj);
                var index_query = layer.confirm('确定移除当前记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var trObj = recordObj.closest(parentTag);// 'tr'
                    trObj.remove();
                    autoCountStaffNum();
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
                    autoCountStaffNum();
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
        // function initSubject(id) {
        //     if(id <= 0) return ;
        //     var data = {};
        //     data['id'] = id;
        //     var layer_index = layer.load();
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : AJAX_SUBJECT_URL,
        //         'data' : data,
        //         'dataType' : 'json',
        //         'success' : function(ret){
        //             console.log('ret',ret);
        //             if(!ret.apistatus){//失败
        //                 //alert('失败');
        //                 err_alert(ret.errorMsg);
        //             }else{//成功
        //                 var subject_list = ret.result.subject_list;
        //                 console.log('subject_list', subject_list);
        //                 // 循环遍历
        //                 for(var subject_k in subject_list) {//遍历json对象的每个key/value对,p为key
        //                     var data_list = {
        //                         'data_list': subject_list[subject_k],
        //                     };
        //                     // 解析数据
        //                     initAnswer(subject_k, data_list, 1);
        //                 }
        //             }
        //             layer.close(layer_index)//手动关闭
        //         }
        //     });
        // }

        // 更新试题
        // id 试题id
        // trObj tr对象
        // function updateSubject(id, trObj) {
        //     if(id <= 0) return ;
        //     var data = {};
        //     data['id'] = id;
        //     var layer_index = layer.load();
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : AJAX_UPDATE_SUBJECT_URL,
        //         'data' : data,
        //         'dataType' : 'json',
        //         'success' : function(ret){
        //             console.log('ret',ret);
        //             if(!ret.apistatus){//失败
        //                 //alert('失败');
        //                 err_alert(ret.errorMsg);
        //             }else{//成功
        //                 var subject_list = ret.result;
        //                 console.log('subject_list', subject_list);
        //                 var data_list = {
        //                     'data_list': subject_list,
        //                 };
        //                 // 解析数据
        //                 var htmlStr = initAnswer('', data_list, 3);
        //                 trObj.after(htmlStr);
        //                 trObj.remove();
        //                 autoCountStaffNum();
        //             }
        //             layer.close(layer_index)//手动关闭
        //         }
        //     });
        // }

        // 初始化答案列表
        // data_list 数据对象 {'data_list':[{}]}
        // type类型 1 全替换 2 追加到后面 3 返回html
        // function initAnswer(class_name, data_list, type){
        //     var htmlStr = resolve_baidu_template(DYNAMIC_BAIDU_TEMPLATE,data_list,'');//解析
        //     if(type == 3) return htmlStr;
        //     //alert(htmlStr);
        //     //alert(body_data_id);
        //     if(type == 1){
        //         $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).html(htmlStr);
        //     }else if(type == 2){
        //         $('.'+ class_name).find('.' + DYNAMIC_TABLE_BODY).append(htmlStr);
        //     }
        // }

        // 获得参考人员数量
        function autoCountStaffNum(){
            var total = 0;
            $('.staff_td').find('.staffs').each(function () {
                var departmentObj = $(this);
                var staff_num = departmentObj.find('.data_list').find('tr').length;
                console.log('staff_num',staff_num);
                departmentObj.find('input[name="staff_num[]"]').val(staff_num);
                departmentObj.find('.staff_num').html(staff_num);
                total += parseInt(staff_num);
            });
            $('.subject_num').html(total);

        }

        // 获得选中的试卷id 数组
        function getSelectedPaperIds(){
            var paper_ids = [];
			var paper_id = $('input[name=paper_id]').val();
			paper_ids.push(paper_id);
            console.log('paper_ids' , paper_ids);
            return paper_ids;
        }

        // 取消
        // paper_id 试卷id
        function removePaper(paper_id){
            var seled_paper_id = $('input[name=paper_id]').val();
            if(paper_id == seled_paper_id){
                $('input[name=paper_id]').val('');
                $('input[name=paper_history_id]').val('');
                $('.paper_name').html('');
                $('.update_paper').hide();
			}
        }

        // 增加
        // paper_id 试题id, 多个用,号分隔
        function addPaper(paper_id){
            if(paper_id == '') return ;
            var data = {};
            data['id'] = paper_id;
            console.log('data', data);
            console.log('AJAX_PAPER_ADD_URL', AJAX_PAPER_ADD_URL);
            var layer_index = layer.load();
            $.ajax({
                'async': false,// true,//false:同步;true:异步
                'type' : 'POST',
                'url' : AJAX_PAPER_ADD_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var paper_info = ret.result;
                        console.log('paper_info', paper_info);
                        $('input[name=paper_id]').val(paper_info.paper_id);
                        $('input[name=paper_history_id]').val(paper_info.paper_history_id);
                        $('.paper_name').html(paper_info.paper_name);
                        var now_paper = paper_info.now_paper;// 最新的试题 0没有变化 ;1 已经删除  2 试卷不同
                        if(now_paper == 2 ){
                            $('.update_paper').show();
						}else{
                            $('.update_paper').hide();
						}
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
        }

        // 获得选中的试题id 数组
        // subject_type 试题类型id
        function getSelectedStaffIds(){
            var subject_ids = [];
            $('.staff_td').find('.department_group_' + subject_type).find('.data_list').find('input[name="subject_ids[]"]').each(function () {
                var subject_id = $(this).val();
                subject_ids.push(subject_id);
            });
            console.log('subject_ids' , subject_ids);
            return subject_ids;
        }

        // 取消
        // subject_type 类型id
        // subject_id 试题id
        // function removeSubject(subject_type, subject_id){
        //     $('.staff_td').find('.department_group_' + subject_type).find('.data_list').find('input[name="subject_ids[]"]').each(function () {
        //
        //         var tem_subject_id = $(this).val();
        //         if(subject_id == tem_subject_id){
        //             $(this).closest('tr').remove();
        //             return ;
        //         }
        //     });
        //     autoCountStaffNum();
        // }

        // 增加
        // subject_type 类型id
        // subject_id 试题id, 多个用,号分隔
        // function addSubject(subject_type, subject_id){
        //     if(subject_id == '') return ;
        //     var data = {};
        //     data['id'] = subject_id;
        //     var layer_index = layer.load();
        //     $.ajax({
        //         'async': false,// true,//false:同步;true:异步
        //         'type' : 'POST',
        //         'url' : AJAX_SUBJECT_ADD_URL,
        //         'data' : data,
        //         'dataType' : 'json',
        //         'success' : function(ret){
        //             console.log('ret',ret);
        //             if(!ret.apistatus){//失败
        //                 //alert('失败');
        //                 err_alert(ret.errorMsg);
        //             }else{//成功
        //                 var subject_list = ret.result;
        //                 console.log('subject_list', subject_list);
        //                 var data_list = {
        //                     'data_list': subject_list,
        //                 };
        //                 // 解析数据
        //                 initAnswer('department_group_' + subject_type, data_list, 2);
        //                 autoCountStaffNum();
        //             }
        //             layer.close(layer_index)//手动关闭
        //         }
        //     });
        // }

	</script>

@endpush
