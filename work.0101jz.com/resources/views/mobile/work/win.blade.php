@extends('layouts.m')

@push('headscripts')
    {{--  本页单独使用 --}}
@endpush

@section('content')

    <div class="page">
        <div id="header">
            <div class="top-title">结单</div>
        </div>

        <form class="am-form am-form-horizontal" method="post"  id="addForm">
            <input type="hidden" name="id" value="{{ $id or 0 }}"/>
        {{--<div class="box">--}}
        <div id="prlblem" class="table4" >

            <div class="bd inp" >

                <div class="gd-list" >
                    <div class="gd-hd">
                        <p>
                            <span class="khname"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i> {{ $customer_name or ''  }}({{ $sex_text or ''  }}) </span>
                    <a href="tel:{{ $call_number or ''  }}" class="btnnb fr" ><i class="fa fa-phone fa-fw" aria-hidden="true"></i> {{ $call_number or ''  }}</a>
            </div>
            <div class="gd-bd">
                <p><i class="fa fa-flag fa-fw" aria-hidden="true"></i>  工单类型：{{ $type_name or ''  }}--{{ $business_name or ''  }}</p>
                <p class="khtip">{!!  $content or ''  !!}
                </p>
                <p>
                    <span class="gdtime"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> 报修时间：{{ $created_at or ''  }}</span>
                    <span class="gdtime"> 到期时间：{{ $expiry_time or ''  }}</span>
                </p>
            </div>
            <div class="gd-fd">
                <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i> {{ $city_name or ''  }}{{ $area_name or ''  }}{{ $address or ''  }}</p>
            </div>
            <div class="gd-fd">
                <dl class="inp">
                    <!-- 					<dt>反馈内容<span class="must">*</span></dt>
                     -->
                    <dd>
                        <textarea type="text" class="inptext wlong"  style=" height:200px"  placeholder="结单内容说明" name="win_content"  />{{ $win_content or '' }}</textarea>
                    </dd>
                </dl>
                <dl>
                    <dt> </dt>
                    <div class="k10"></div>
                    <dd><button class="btn btn-l wlong"  id="submitBtn" >结单</button>
                    </dd>
                </dl>

            </div>
        </div>

            </div>
        </div>

        </form>
        @include('mobile.layout_public.menu', ['menu_id' => 5])


    </div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    {{--<script src="{{asset('staticmobile/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('staticmobile/js/sidebar-menu.js')}}"></script>
    <script>
        $.sidebarMenu($('.sidebar-menu'))
    </script>

    <script type="text/javascript">
        const SAVE_URL = "{{ url('api/m/work/ajax_win') }}";// ajax保存记录地址
        const LIST_URL = "{{url('m')}}";//保存成功后跳转到的地址
    </script>
    <script src="{{ asset('/js/m/lanmu/work_win_edit.js') }}"  type="text/javascript"></script>
@endpush
