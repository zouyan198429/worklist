@extends('layouts.m')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div class="page">
		<div id="header">
			<div class="top-back"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i></a></div>
			<div class="top-title">详情页</div>
		</div>
		<section class="main" id="know-view" >
			<h1>{{ $title or '' }}</h1>
			<div class="content">
				{!! $content or '' !!}

			</div>
			<p class="tip">阅读：{{ $volume or '' }}  上传：{{ $real_name or '' }}</p>
			<div class="fanye">
				@if (count($preList) > 0)
					<p>上一篇：
						@foreach ($preList as $pre)
							<a href="javascript:void(0);"   onclick="action.near({{ $pre['id'] or '' }})">{{ $pre['title'] or '' }}</a>
						@endforeach
					</p>
				@else
					<p>上一篇：已经是第一篇了</p>
				@endif
				@if (count($nextList) > 0)
					<p>下一篇：
						@foreach ($nextList as $next)
							<a href="javascript:void(0);"  onclick="action.near({{ $next['id'] or '' }})">{{ $next['title'] or '' }}</a>
						@endforeach
					</p>
				@else
					<p>下一篇：已经是最后一篇了</p>
				@endif
			</div>

	</div>
	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
	<script>
        var SHOW_URL = "{{url('m/lore/info/')}}/";//显示页面地址前缀 + id
        var SUBMIT_FORM = true;//防止多次点击提交

        //业务逻辑部分
        var action = {
            near : function(id){// 上一条，下一条
                console.log('id', id);
                //获得表单各name的值
                // var data = parent.get_frm_values(parent.SURE_FRM_IDS);// {}
                // console.log(SHOW_URL);
                // console.log(data);
                 var url_params = "type_id={{ $type_id or 0 }}";//parent.get_url_param(data);
                var url = SHOW_URL + id + '?' + url_params;
                console.log(url);
                go(url);
                // location.href = url;// '/pms/Supplier/show?supplier_id='+id;
                return false;
            }
        };
	</script>
@endpush