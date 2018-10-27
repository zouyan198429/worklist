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
					<input type="text" class="inp wlong exam_begin_time" name="exam_begin_time" value="{{ $exam_begin_time or '' }}" placeholder="请选择开考时间" autofocus  required />
				</td>
			</tr>
			<tr>
				<th>考试时长(分)</th>
				<td>
					<input type="text" class="inp wlong" name="exam_minute" value="{{ $exam_minute or '' }}" placeholder="请输入考试时长" autofocus  required  onkeyup="isnum(this) " onafterpaste="isnum(this)" />
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
					<input type="text" class="inp wlong" name="pass_score" value="{{ $pass_score or '' }}" placeholder="请输入及格分数" autofocus  required  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>

				</td>
			</tr>
			<tr>
				<th>参与人员</th>
				<td>
					共 <span class="subject_num">{{ $subject_num or '0' }}</span> 人
					<button class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectStaff(this)">选择人员</button>
				</td>
			</tr>
			<tr>
				<td colspan="2"  class="staff_td">
					<div class="table-header">
						<button class="btn btn-danger  btn-xs ace-icon fa fa-trash-o bigger-60"  onclick="otheraction.batchDel(this, '.staff_td', 'tr')">批量删除</button>
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
						</tbody>

					</table>

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
    <script type="text/javascript" src="{{asset('laydate/laydate.js')}}"></script>
	<script type="text/javascript">
        var SAVE_URL = "{{ url('api/manage/exam/ajax_save') }}";// ajax保存记录地址
        var LIST_URL = "{{url('manage/exam')}}";//保存成功后跳转到的地址

        var ID_VAL = "{{ $id or 0 }}";// 当前id值
        var AJAX_STAFF_URL = "{{ url('api/manage/exam/ajax_get_staff') }}";// ajax初始化参考人员地址
        var AJAX_UPDATE_STAFF_URL = "{{ url('api/manage/exam/ajax_add_staff') }}";// ajax更新参考人员地址
        var AJAX_STAFF_ADD_URL = "{{ url('api/manage/exam/ajax_add_staff') }}";// ajax添加参考人员地址
        var SELECT_STAFF_URL = "{{ url('manage/staff/select') }}";// 选择参考人员地址
        var SELECT_PAPER_URL = "{{ url('manage/paper/select') }}";// 选择试卷地址
		var AJAX_PAPER_ADD_URL = "{{ url('api/manage/exam/ajax_add_paper') }}";// ajax添加/修改试卷地址
        var DYNAMIC_BAIDU_TEMPLATE = "baidu_template_data_list";//百度模板id
        var DYNAMIC_TABLE_BODY = "data_list";//数据列表class


        var BEGIN_TIME = "{{ $exam_begin_time or '' }}" ;//开考时间
	</script>
	{{--<script src="{{ asset('/js/manage/lanmu/exam_edit.js') }}"  type="text/javascript"></script>--}}
	<script type="text/javascript">

        var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            //执行一个laydate实例
            // 开始日期
            laydate.render({
                elem: '.exam_begin_time' //指定元素
                ,type: 'datetime'
                ,value: BEGIN_TIME// '2018-08-18' //必须遵循format参数设定的格式
                ,min: get_now_format()//'2017-1-1'
                //,max: get_now_format()//'2017-12-31'
                ,calendar: true//是否显示公历节日
            });
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
            initStaff(ID_VAL);// 页面初始化员工
        });
        //ajax提交表单
        function ajax_form(){
            if (!SUBMIT_FORM) return false;//false，则返回

            // 验证信息
            var id = $('input[name=id]').val();
            if(!judge_validate(4,'记录id',id,true,'digit','','')){
                return false;
            }

            // 场次
            var exam_num = $('input[name=exam_num]').val();
            if(!judge_validate(4,'场次',exam_num,true,'length',2,50)){
                return false;
            }

            // 考试主题
            var exam_subject = $('input[name=exam_subject]').val();
            if(!judge_validate(4,'考试主题',exam_subject,true,'length',2,100)){
                return false;
            }

            // 开考时间
            var begin_date = $('input[name=exam_begin_time]').val();
            if(!judge_validate(4,'开考时间',begin_date,true,'date','','')){
                return false;
            }

            var now_time = get_now_format();
            if( !judge_validate(4,'开考时间必须',begin_date,true,'data_size',now_time,5)){
                return false;
            }

            // 考试时长(分)
            var exam_minute = $('input[name=exam_minute]').val();
            if(!judge_validate(4,'考试时长',exam_minute,true,'positive_int','','')){
                return false;
            }

            // 试卷
            var paper_id = $('input[name=paper_id]').val();
            var judge_seled = judge_validate(1,'试卷',paper_id,true,'positive_int','','');
            if(judge_seled != ''){
                layer_alert("请选择试卷",3,0);
                return false;
            }

            var paper_history_id = $('input[name=paper_history_id]').val();
            var judge_seled = judge_validate(1,'试卷',paper_history_id,true,'positive_int','','');
            if(judge_seled != ''){
                layer_alert("请选择试卷",3,0);
                return false;
            }

            // 及格分数
            var pass_score = $('input[name=pass_score]').val();
            if(!judge_validate(4,'及格分数',pass_score,true,'doublepositive','','')){
                return false;
            }


            // 判断考试员工
            var staff_num = $('.staff_td').find('.data_list').find('tr').length;
            if(staff_num <= 0){
                layer_alert("请选择参加考试的员工",3,0);
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
            selectPaper: function(obj){// 选择试卷
                var recordObj = $(obj);
                //获得表单各name的值
                var weburl = SELECT_PAPER_URL;
                console.log(weburl);
                // go(SHOW_URL + id);
                // location.href='/pms/Supplier/show?supplier_id='+id;
                // var weburl = SHOW_URL + id;
                // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
                var tishi = '选择试卷';//"查看供应商";
                console.log('weburl', weburl);
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
            selectStaff: function(obj){// 选择员工
                var recordObj = $(obj);
                //获得表单各name的值
                var weburl = SELECT_STAFF_URL;
                console.log(weburl);
                // go(SHOW_URL + id);
                // location.href='/pms/Supplier/show?supplier_id='+id;
                // var weburl = SHOW_URL + id;
                // var weburl = '/pms/Supplier/show?supplier_id='+id+"&operate_type=1";
                var tishi = '选择员工';//"查看供应商";
                console.log('weburl', weburl);
                layeriframe(weburl,tishi,950,600,0);
                return false;
            },
            edit : function(obj, parentTag, staff_id){// 更新员工
                var recordObj = $(obj);
                var index_query = layer.confirm('确定更新当前记录？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var trObj = recordObj.closest(parentTag);// 'tr'
                    updateStaff(staff_id,trObj);
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

        // 页面初始化员工
		// id 考试id
        function initStaff(id) {
            if(id <= 0) return ;
            var data = {};
            data['exam_id'] = id;
            var layer_index = layer.load();
            console.log(data);
            console.log(AJAX_STAFF_URL);
            $.ajax({
                'type' : 'POST',
                'url' : AJAX_STAFF_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var staff_list = ret.result;
                        console.log('staff_list', staff_list);
                        // 解析数据
                        initAnswer('staff_td', staff_list, 1);
                        autoCountStaffNum();
                    }
                    layer.close(layer_index)//手动关闭
                }
            });
        }

        // 更新试题
        // id 试题id
        // trObj tr对象
        function updateStaff(id, trObj) {
            if(id <= 0) return ;
            var data = {};
            data['id'] = id;
            var layer_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : AJAX_UPDATE_STAFF_URL,
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
                        autoCountStaffNum();
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

        // 获得参考人员数量
        function autoCountStaffNum(){
            var total = 0;
            $('.staff_td').each(function () {
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

        // 获得员工id 数组
        function getSelectedStaffIds(){
            var staff_ids = [];
            $('.staff_td').find('.data_list').find('input[name="staff_ids[]"]').each(function () {
                var staff_id = $(this).val();
                staff_ids.push(staff_id);
            });
            console.log('staff_ids' , staff_ids);
            return staff_ids;
        }

        // 取消
        // staff_id 试题id
        function removeStaff(staff_id){
            $('.staff_td').find('.data_list').find('input[name="staff_ids[]"]').each(function () {

                var tem_staff_id = $(this).val();
                if(staff_id == tem_staff_id){
                    $(this).closest('tr').remove();
                    return ;
                }
            });
            autoCountStaffNum();
        }

        // 增加
        // staff_id 试题id, 多个用,号分隔
        function addStaff( staff_id){
            console.log('addStaff', staff_id);
            if(staff_id == '') return ;
            // 去掉已经存在的记录id
            var selected_ids = getSelectedStaffIds();
            var staff_id_arr = staff_id.split(",");
            //差集
            var diff_arr = staff_id_arr.filter(function(v){ return selected_ids.indexOf(v) == -1 });
            staff_id = diff_arr.join(',');
            if(staff_id == '') return ;

            var data = {};
            data['id'] = staff_id;
            var layer_index = layer.load();
            $.ajax({
                'async': false,// true,//false:同步;true:异步
                'type' : 'POST',
                'url' : AJAX_STAFF_ADD_URL,
                'data' : data,
                'dataType' : 'json',
                'success' : function(ret){
                    console.log('ret',ret);
                    if(!ret.apistatus){//失败
                        //alert('失败');
                        err_alert(ret.errorMsg);
                    }else{//成功
                        var staff_list = ret.result;
                        console.log('staff_list', staff_list);
                        var data_list = {
                            'data_list': staff_list,
                        };
                        // 解析数据
                        initAnswer('staff_td', data_list, 2);
                        autoCountStaffNum();
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
        var now_staff = item.now_staff;
		can_modify = true;
		%>
		<tr>
			<td>
				<label class="pos-rel">
					<input onclick="otheraction.seledSingle(this , '.table2')" type="checkbox" class="ace check_item" value="<%=item.staff_id%>">
					<span class="lbl"></span>
				</label>
				<input type="hidden" name="staff_ids[]" value="<%=item.staff_id%>"/>
				<input type="hidden" name="staff_history_ids[]" value="<%=item.staff_history_id%>"/>
				<input type="hidden" name="department_ids[]" value="<%=item.department_id%>"/>
				<input type="hidden" name="department_names[]" value="<%=item.department_name%>"/>
				<input type="hidden" name="group_ids[]" value="<%=item.group_id%>"/>
				<input type="hidden" name="group_names[]" value="<%=item.group_name%>"/>
				<input type="hidden" name="position_ids[]" value="<%=item.position_id%>"/>
				<input type="hidden" name="position_names[]" value="<%=item.position_name%>"/>
			</td>
			<td><%=item.work_num%></td>
			<td><%=item.department_name%>/<%=item.group_name%></td>
			<td><%=item.real_name%></td>
			<td><%=item.sex_text%></td>
			<td><%=item.position_name%></td>
			<td><%=item.mobile%></td>
			<td>
            <%if( now_staff == 2 || now_staff == 4){%>
				<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.edit(this, 'tr', <%=item.staff_id%>)">
					<i class="ace-icon fa fa-pencil bigger-60 pink"> 更新[员工已更新]</i>
				</a>
            <%}%>
            <%if( now_staff == 1){%>
				<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
					<i class="ace-icon fa fa-trash-o bigger-60 wrong"> 删除[员工已删]</i>
				</a>
            <%}%>
				<a href="javascript:void(0);" class="btn btn-mini btn-info" onclick="otheraction.del(this, 'tr')">
					<i class="ace-icon fa fa-trash-o bigger-60"> 移除</i>
				</a>
			</td>
		</tr>
		<%}%>
	</script>
	<!-- 列表模板部分 结束-->
	<!-- 前端模板结束 -->
@endpush
