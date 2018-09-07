@extends('layouts.app')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')
<div class="content-header">
    <ul class="breadcrumb">
        <li><a href="#"><i class="icon icon-home"></i></a></li>
        <li class="active">首页</li>
    </ul>
</div>
<div class="content-body">
    <div class="container-fluid">
         <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="panel"  style="min-height: 260px;">
                    <div class="panel-heading">
                        <div class="panel-title">用户指南</div>
                    </div>
                    <div class="panel-body">
                        新用户请先完成以下工作：<br />
                        1. 完善企业信息；<br />
                        2. 完成企业资质认证；<br />
                        3. 新建第一个生产单元。<br />
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="panel"  style="min-height: 260px;">
                    <div class="panel-heading">
                        <div class="panel-title">常见问题</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-info">
                            <tr>
                                <td>问题：</td>
                             </tr>
                            <tr>
                                <td>问题：</td>
                             </tr>
                            <tr>
                                <td>问题：</td>
                             </tr>
                            <tr>
                                <td>问题：</td>
                             </tr>
                            <tr>
                                <td>问题：</td>
                             </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{--111   <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-primary">
                    <div class="info-box-icon">
                        <i class="icon icon-user"></i>
                    </div>
                    <div class="info-box-content">
                        <span class="info-box-text">用户总量</span>
                                <span class="info-box-number">{{ $visitUniqueCount or 0 }}
                                    <small>个</small>
                                </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-info">
                    <div class="info-box-icon">
                        <i class="icon icon-file-text"></i>
                    </div>
                    <div class="info-box-content">
                        <span class="info-box-text">日志总量</span>
                                <span class="info-box-number">{{ $recordCount or 0 }}
                                    <small>篇</small>
                                </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-warning">
                    <div class="info-box-icon">
                        <i class="icon icon-bars"></i>
                    </div>
                    <div class="info-box-content">
                        <span class="info-box-text">生产单元</span>
                                <span class="info-box-number">{{ $unitCount or 0 }}
                                    <small>个</small>
                                </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-danger">
                    <div class="info-box-icon">
                        <i class="icon icon-eye-open"></i>
                    </div>
                    <div class="info-box-content">
                        <span class="info-box-text">微站访问</span>
                                <span class="info-box-number">{{ $visitCount or 0 }}
                                    <small>次</small>
                                </span>
                    </div>
                </div>
            </div> --}}
        <div class="row">
            <div class="col-md-4">
                <div class="panel"  style="min-height: 260px;">
                    <div class="panel-heading">
                        <div class="panel-title">帐户信息</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-info">
                            <tr>
                                <td>用户名</td>
                                <td>{{ $userInfo['account_username'] or '' }}</td>
                            </tr>
                            {{--
                            <tr>
                                <td>帐户等级</td>
                                <td><span class="am-text-success" >试用期</span>[2018-07-04 到期]</td>
                            </tr>
                            <tr>
                                <td>资质认证</td>
                                <td>未认证 <a href="{{ url('company/') }}">【现在认证】</a> / 完成 </td>
                            </tr>
                            --}}
                            <tr>
                                <td>会员使用时间</td>
                                <td>{{ date('Y-m-d',strtotime($userInfo['created_at'])) }} </td>
                            </tr>
                            <tr>
                                <td>注册时间</td>
                                <td>{{ date('Y-m-d',strtotime($userInfo['created_at'])) }} </td>
                            </tr>
                            <tr>
                                <td>上次登录 </td>
                                <td>{{ $userInfo['lastlogintime'] or '' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>




            <div class="col-md-4">
                <div class="panel" style="min-height: 260px;">
                    <div class="panel-heading">
                        <div class="panel-title">平台公告</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-info">
                            @foreach ($newList as $new)
                            <tr>
                                <td><a href="{{ url('new/' . $new['id']) }}" >{{ $new['new_title'] or '' }}</a></td>
                                <td>{{ date('Y-m-d',strtotime($new['updated_at'])) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel" style="min-height: 260px;">
                    <div class="panel-heading">
                        <div class="panel-title">平台信息</div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-info">
                            <tr>
                                <td>系统名称</td>
                                <td>{{ $configArr[1]['site_val'] or '' }}</td>
                            </tr>
                            <tr>
                                <td>开发运营</td>
                                <td>杨凌沃太农业咨询有限公司</td>
                            </tr>
                            <tr>
                                <td>电子邮箱</td>
                                <td>{{ $configArr[5]['site_val'] or '' }}</td>
                            </tr>
                            <tr>
                                <td>在线客服</td>
                                <td>QQ：{{ $configArr[9]['site_val'] or '' }} 电话：{{ $configArr[4]['site_val'] or '' }}</td>
                            </tr>
                            <tr>
                                <td>公司地址</td>
                                <td>{{ $configArr[3]['site_val'] or '' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        @foreach ($siteIntro as $info)
                        <a href="{{ url('sys/help/' . $info['id']) }}"> <i class="icon icon-caret-right"></i> {{ $info['intro_title'] }}</a>
                        @endforeach
                        <span class="pull-right text-gray">版权所有：{{ $configArr[1]['site_val'] or '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('footscripts')
@endpush