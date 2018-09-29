@extends('layouts.weixiu')

@push('headscripts')
    {{--  本页单独使用 --}}
@endpush

@section('content')

    <div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线反馈</div>
    <div class="mm">
        <form class="am-form am-form-horizontal" method="post"  id="addForm">
            <input type="hidden" name="id" value="{{ $id or 0 }}"/>
            <table class="table1">
                <tr>
                    <th>问题分类<span class="must">*</span></th>
                    <td>

                        <select class="wnormal" name="work_type_id" >
                            <option  value="">请选择问题</option>
                            @foreach ($problemFirstList as $k=>$txt)
                                <option value="{{ $k }}"  @if(isset($work_type_id) && $work_type_id == $k) selected @endif >{{ $txt }}</option>
                            @endforeach
                        </select>
                        <select class="wnormal" name="business_id" >
                            <option value="">请选择部门</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>反馈内容<span class="must">*</span></th>
                    <td>
                        <textarea type="text" class="inptext wlong" name="content" />{{ $content or '' }}</textarea>
                    </td>
                </tr>
            </table>
            <div class="line"></div>
             <table class="table1" style="display: none;">
                <tr>
                    <th>客户电话</th>
                    <td>
                        <input type="number" class="inp wnormal" name="call_number" value="{{ $call_number or '' }}" placeholder="来电号码" autofocus  required />
                    </td>
                </tr>
            </table>
            <div class="line"></div>
            <table class="table1">
                <tr style="display: none;">
                    <th>客户地址</th>
                    <td>
                        <select class="wmini" name="city_id" onchange="getAreaArr()">
                            <option value="">请选择区县</option>
                            @foreach ($areaCityList as $k=>$txt)
                                <option value="{{ $k }}"  @if(isset($city_id) && $city_id == $k) selected @endif >{{ $txt }}</option>
                            @endforeach
                        </select>
                        <select class="wmini" name="area_id">
                            <option value="">请选择街道</option>
                        </select>
                        <input type="text" class="inp wnormal" name="address"    value="{{ $address or '' }}" />
                    </td>
                </tr> 

                <tr>
                    <th> </th>
                    <td><button class="btn btn-l wnormal" id="submitBtn" >提交</button></td>
                </tr>

            </table>
        </form>
    </div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
    <script type="text/javascript">
        const SAVE_URL = "{{ url('api/weixiu/problem/ajax_save') }}";// ajax保存记录地址
        const LIST_URL = "{{url('weixiu/problem/add/0')}}";//保存成功后跳转到的地址
        const WORKTYPE_CHILD_URL = "{{ url('api/weixiu/problem_type/ajax_get_child') }}";// 维修类型二级分类请求地址
        const AREA_CHILD_URL = "{{ url('api/weixiu/area/ajax_get_child') }}";// 区县二级分类请求地址
        const WORK_TYPE_ID = "{{ $work_type_id or 0}}";// 维修类型-默认值
        const BUSINESS_ID = "{{ $business_id or 0 }}";// 维修类型二级--默认值
        const CITY_ID = "{{ $city_id or 0}}";// 县区id默认值
        const AREA_ID = "{{ $area_id or 0 }}";// 街道默认值

        $(function(){
            // 当前的维修类型
            @if (isset($work_type_id) && $work_type_id >0 )
            changeFirstSel(REL_CHANGE.work_type,WORK_TYPE_ID,BUSINESS_ID, false);
            @endif

            {{--// 当前的客户地址--}}
            {{--@if (isset($city_id) && $city_id >0 )--}}
            {{--changeFirstSel(REL_CHANGE.area_city,CITY_ID,AREA_ID, false);--}}
            {{--@endif--}}
        });
    </script>
    <script src="{{ asset('/js/weixiu/lanmu/problem_edit.js') }}"  type="text/javascript"></script>
@endpush