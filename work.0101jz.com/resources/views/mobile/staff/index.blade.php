@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">

		<div id="header">
			<div class="top-title">个人中心</div>
		</div>
		<div class="infohd">
			<div id="infotx"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> </div>
			<div id="infoname">
				<h2>{{ $real_name or '' }} <span> 工号：{{ $work_num or '' }}</span></h2>
				<h4>部门：{{ $department_name or '' }}/
					{{ $group_name or '' }}</h4>
			</div>
			<div class="c"></div>
		</div>
		<section class="wrap" id="study" >
			@if(isset($webType) && $webType == 2)
			<div class="myachieve">
				@foreach ($sumStatus as $k=>$v)
					@if(in_array($k,$ajaxSumStatus))
					<dl>
						<dt>{{ $v['name'] or '' }}工单</dt>
						<dd class="status_sum_{{ $k }}" data-old_count="0" >0</dd>
					</dl>
					@endif
				@endforeach
				{{--
				<dl>
					<dt>今日工单</dt>
					<dd>6</dd>
				</dl>
				--}}
				<div class="c"></div>

			</div>
			@endif

			<div class="mynav">
				<ul>
					<li>
						<a href="{{ url('m/password') }}">
							<span>修改密码</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					<li>
						<a href="{{ url('m/staff/list') }}">
							<span>我的同事</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					<li>
						<a href="{{ url('m/help') }}">
							<span>帮助中心</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					@if(isset($webType) && $webType == 2)
					<li>
						<a href="{{ url('m/notice') }}">
							<span>通知公告</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					@endif
					<li>
						<a href="{{ url('m/problem') }}">
							<span>我的反馈</span>
							<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
						</a>
						<div class="c"></div>
					</li>
					<li>
						<a href="{{ url('m/logout') }}"><span>退出</span></a>
						<div class="c"></div>
					</li>

				</ul>
			</div>
		</section>

		@include('mobile.layout_public.menu', ['menu_id' => 5])



	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script type="text/javascript">
        const SATUS_SUM_URL = "{{ url('api/m/work/ajax_work_sum') }}";// ajax工单状态统计 url
        const AJAX_SUM_STATUS = "{{ implode(',',$ajaxSumStatus) }}";// 需要发声/ajax请求的状态，多个逗号,分隔

        // var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            ajax_status_sum(0, 0, 0);//ajax工单状态统计
        });

        //ajax工单处理数量统计
        // from_id 来源 0 页面第一次加载,不播放音乐 1 每分钟获得数量，有变化，播放音乐

        function ajax_status_sum(from_id ,staff_id, operate_staff_id){
            // if (!SUBMIT_FORM) return false;//false，则返回

            // 验证通过
            //  SUBMIT_FORM = false;//标记为已经提交过
            var data = {
                'staff_id': staff_id,
                'operate_staff_id': operate_staff_id,
            };
            data['sum_status'] = AJAX_SUM_STATUS;
            console.log(SATUS_SUM_URL);
            console.log(data);
            if( from_id == 0)  var layer_count_index = layer.load();
            $.ajax({
                'type' : 'POST',
                'url' : SATUS_SUM_URL,
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
                        // var doStatus = AJAX_SUM_STATUS;
                        // var doStatusArr = doStatus.split(',');

                        // 遍历
                        for(var temStatus in statusCount){//遍历json对象的每个key/value对,p为key
                            var countObj = $(".status_sum_" + temStatus );
                            if(countObj.length <= 0) continue;
                            var temCount = statusCount[temStatus].amount;
                            var oldCount = countObj.data('old_count');
                            console.log('oldCount',oldCount);
                            console.log('temCount',temCount);
                            if(oldCount != temCount){
                                countObj.html(temCount);
                                countObj.data('old_count', temCount);
                                console.log('oldCount--',$(".status_sum_" + temStatus ).data('old_count'));
                            }
                        }

                    }
                    // SUBMIT_FORM = true;//标记为未提交过
                    if( from_id == 0)   layer.close(layer_count_index);//手动关闭
                }
            });
            return false;
        }


	</script>
@endpush