
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>总部客服平台</title>


    <!-- zui css -->
    <link rel="stylesheet" href="http://work.kefu.cunwo.net/dist/css/zui.min.css">
    <link rel="stylesheet" href="http://work.kefu.cunwo.net/statichuawu/css/style.css">
    <link rel="stylesheet" href="http://work.kefu.cunwo.net/statichuawu/css/sidebar-menu.css">

    <link rel="stylesheet" href="http://work.kefu.cunwo.net/font-awesome-4.7.0/css/font-awesome.css">
    <style type="text/css">
        .main-sidebar{
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            min-height: 100%;
            width: 200px;
            z-index: 810;
            background-color: #222d32;
        }
    </style>
    <script src="http://work.kefu.cunwo.net/statichuawu/js/jquery-2.1.1.min.js" type="text/javascript"></script>


</head><body>

<div id="main">

    <div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的客户</div>
    <div class="mm">
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p>一次最多上传9张图片。</p>
        </div>
        <form class="am-form am-form-horizontal" method="post"  id="addForm">
            <input type="hidden" name="id" value="0"/>
            <table class="table1">
                <tr>
                    <th>上传图片</th>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="resourceBlock">
                                    <div  class="cards upload_img">

                                    </div>

                                    <div id='myUploader' class="uploader" data-ride="uploader" data-url="http://work.kefu.cunwo.net/api/huawu/upload">
                                        <div class="uploader-message text-center">
                                            <div class="content"></div>
                                            <button type="button" class="close">×</button>
                                        </div>
                                        <div class="uploader-files file-list file-list-grid"></div>
                                        <div>
                                            <hr class="divider">
                                            <div class="uploader-status pull-right text-muted"></div>
                                            <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> 选择文件</button>
                                            <button type="button" class="btn btn-link uploader-btn-start"><i class="icon icon-cloud-upload"></i> 开始上传</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

</body>
</html>

<!-- BaiduTemplate -->


<!-- 模态框（Modal） -->

<!-- /.main-container -->
<!-- basic scripts -->



<!-- page specific plugin scripts -->
<script src="http://work.kefu.cunwo.net/static/js/jquery.dataTables.min.js"></script>



<!-- 新加入 begin-->
<script src="http://work.kefu.cunwo.net/static/js/moment.min.js"></script>

<script src="http://work.kefu.cunwo.net/static/js/jquery-ui.min.js"></script>

<script src="http://work.kefu.cunwo.net/static/js/custom/data_tables.js"></script>

<!-- 数据验证-->

<!-- 弹出层-->
<!-- BaiduTemplate-->
<script src="http://work.kefu.cunwo.net/static/js/custom/baiduTemplate.js"></script>
<!-- 弹出层-->
<script src="http://work.kefu.cunwo.net/static/js/custom/layer/layer.js"></script>
<!-- 公共方法-->
<script src="http://work.kefu.cunwo.net/static/js/custom/common.js"></script>
<!-- ajax翻页方法-->
<script src="http://work.kefu.cunwo.net/static/js/custom/ajaxpage.js"></script>
<!-- 新加入 end--><script src="http://work.kefu.cunwo.net/statichuawu/js/sidebar-menu.js"></script>
<script>
    $.sidebarMenu($('.sidebar-menu'))
</script>
<script type="text/javascript" src="http://work.kefu.cunwo.net/laydate/laydate.js"></script>
<script type="text/javascript">

    const SAVE_URL = "http://work.kefu.cunwo.net/api/huawu/work/ajax_save";// ajax保存记录地址
    const LIST_URL = "http://work.kefu.cunwo.net/huawu/work";//保存成功后跳转到的地址
    const DEPARTMENT_CHILD_URL = "http://work.kefu.cunwo.net/api/huawu/department/ajax_get_child";// 部门二级分类请求地址
    const GROUP_CHILD_URL = "http://work.kefu.cunwo.net/api/huawu/staff/ajax_get_child";// 部门组获得员工---二级分类请求地址
    const WORKTYPE_CHILD_URL = "http://work.kefu.cunwo.net/api/weixiu/work_type/ajax_get_child";// 维修类型二级分类请求地址
    const AREA_CHILD_URL = "http://work.kefu.cunwo.net/api/weixiu/area/ajax_get_child";// 区县二级分类请求地址
    const BOOK_TIME = "2018-10-31 09:38:24" ;//预约处理时间
    const WORK_TYPE_ID = "0";// 维修类型-默认值
    const BUSINESS_ID = "0";// 维修类型二级--默认值
    const CITY_ID = "0";// 县区id默认值
    const AREA_ID = "0";// 街道默认值
    const SEND_DEPARTMENT_ID = "0";// 部门默认值
    const SEND_GROUP_ID = "0";// 小组默认值
    const SEND_STAFF_ID = "0";// 指派员工默认值

    $(function(){
        // 九张图片上传
        $('#myUploader').uploader({
            autoUpload: false,            // 当选择文件后立即自动进行上传操作
            url: "http://work.kefu.cunwo.net/api/huawu/upload",  // 文件上传提交地址 'your/file/upload/url'
            file_data_name:'photo',//	文件域在表单中的名称	默认 'file'
            multipart_params:{pro_unit_id:'0'},
            //{//multipart 附加参数	函数或对象，默认 {}
            // foo: 'foo',
            //bar: ['bar1', 'bar2'],
            //test: {
            //    attr1: 'attr1 value'
            //}
            //},
            //  staticFiles: [
            //  {id: 1, name: 'icon-shop.png', size: 216159, type:'image/jpeg', url: 'http://comp.kezhuisu.net/img/icon-shop.png'},
            //  {id: 2,name: 'icon-shop.png', size: 106091, type:'image/jpeg', url: 'http://comp.kezhuisu.net/img/icon-shop.png'}
            //  ],
            filters:{
// 只允许上传图片或图标（.ico）
                mime_types: [
                    {title: '图片', extensions: 'jpg,gif,png'},
                    {title: '图标', extensions: 'ico'}
                ],
// 最大上传文件为 2MB
                max_file_size: '2mb',
// 不允许上传重复文件
// prevent_duplicates: true
            },
            //{
            // 只允许上传图片或图标（.ico）
            //    mime_types: [
            //        {title: '图片', extensions: 'jpg,gif,png'},
            //        {title: '图标', extensions: 'ico'}
            //    ],
            //    // 最大上传文件为 2MB
            //    max_file_size: '2mb',
            // 不允许上传重复文件
            // prevent_duplicates: true
            //},
            // removeUploaded:true,//	移除已上传文件	false（默认）或 true
            onFilesAdded:function(files){
                console.log('onFilesAdded当文件被添加到上传队列时触发', files);
                console.log('files count', files.length);
                var fileCounts = files.length;// 当前文件数
                var fileObj = this;
                // 自定义的可以上传的总数，一直不变动
                console.log('this对象不变动的总数limitSumCount', fileObj.options.limitSumCount);
                console.log('this对象变动的总数limitFilesCount', fileObj.options.limitFilesCount);
                var limitfilecount = fileObj.options.limitFilesCount;
                console.log('开始判断数量');
                if(limitfilecount !== false){
                    console.log('已进入');
                    // 获得复选框的值
                    var selImgObj = $('#myUploader').closest('.resourceBlock').find(".upload_img");
                    console.log('开始判断数量',selImgObj);
                    console.log('selImgObj.length',selImgObj.length);
                    var checkvalues = get_list_checked(selImgObj,3,1);
                    console.log('已经上传的值ids值', checkvalues);
                    var checkedCount = 0;
                    if(checkvalues != ''){
                        var checked_ids_arr = checkvalues.split(',');
                        console.log('已经上传的记录', checked_ids_arr);
                        var checkedCount = checked_ids_arr.length;
                    }
                    console.log('已经上传的记录数量', checkedCount);
                    var limitSumCount = fileObj.options.limitSumCount;
                    // 真实需要上传的数量
                    var need_upload_count = parseInt(limitSumCount) - parseInt(checkedCount);
                    if(need_upload_count < fileCounts){ // 删除多余的文件对象
                        for (var i = need_upload_count; i < files.length; i++) {
                            fileObj.removeFile(files[i]);// 将文件从文件队列中移除
                        }
                        fileObj.showMessage('所有文件数目不能超过' + limitSumCount + '个，如果要上传此文件请先从列表移除文件。', 'warning', 10000);

                    }
                    // 需要的值不等，就改成正确的
                    if(need_upload_count != limitfilecount){
                        console.log('修改可以上传的最大数为', need_upload_count);
                        fileObj.options.limitFilesCount = need_upload_count;
                    }
                    console.log('真实还需要上传的数量', need_upload_count);
                }

            },
            deleteActionOnDone: function(file, doRemoveFile){
                console.log('deleteActionOnDone删除上传图片成功', file);
                console.log('deleteActionOnDone删除上传图片成功', file.remoteData.id);
                // 删除资源记录
                var resource_id = file.remoteData.id;
                var index_query = layer.confirm('您确定删除吗？不可恢复!', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    layer.close(index_query);
                    delResource(resource_id, 1, doRemoveFile,'myUploader');

                }, function(){
                });
            },//	是否允许删除上传成功的文件	默认 false
            // deleteConfirm:true, //	移除文件进行确认	false（默认）或字符串
            limitSumCount:9,// 自定义的可以上传的总数，一直不变动
            limitFilesCount:9, // 限制文件上传数目	false（默认）或数字
            multi_selection:true,//	是否可用一次选取多个文件	默认 true
            flash_swf_url:"http://work.kefu.cunwo.net/dist/lib/uploader/Moxie.swf",// flash 上传组件地址	默认为 lib/uploader/Moxie.swf
            silverlight_xap_url:"http://work.kefu.cunwo.net/dist/lib/uploader/Moxie.xap",// silverlight 上传组件地址	默认为 lib/uploader/Moxie.xap	请确保在文件上传页面能够通过此地址访问到此文件。
            onFileUploaded: function(file, responseObject) {// 当队列中的一个文件上传完成后触发
                console.log('onFileUploaded上传成功', responseObject);
                if( 1 ){
                    var responseObj = $.parseJSON( responseObject.response );
                    console.log('onFileUploaded上传成功remoteData',responseObj);
                    var htmlStr = '';//
                    htmlStr = resolve_baidu_template('baidu_template_pic_show',responseObj,'');
                    $('#myUploader').closest('.resourceBlock').find(".upload_img").append(htmlStr);
                    this.removeFile(file);// 将文件从文件队列中移除
                    // 单个上传成功后执行方法
                }

            },
            onUploadComplete: function(file) {// 当队列中所有文件上传完成后触发
                console.log('onUploadComplete上传成功', file);
                for (var i = 0; i < file.length; i++) {
                    var temfile = file[i];
                    console.log('local_id:', temfile.id);
                    console.log('remoteId:', temfile.remoteId);
                }
                // 所有上传成功后执行方法
            },
            onUploadFile: function(file) {// 当队列中的某个文件开始上传时触发
                console.log('onUploadFile上传成功', file);
            },
            onError: function(error) {// 当队列中的某个文件开始上传时触发
                console.log('onError', error);
            },
            onFilesRemoved: function(files) {// 当文件从上传队列移除后触发
                console.log('onFilesRemoved', files);
            }
        });
//自定义的-- 删除
        $(document).on("click",".delResource",function(){
            var obj = $(this);
            var index_query = layer.confirm('您确定删除吗？不可恢复!', {
                btn: ['确定','取消'] //按钮
            }, function(){
                // 删除资源记录
                resource_id = obj.data('id');
                layer.close(index_query);
                delResource(resource_id, 2, obj.closest('.resource') ,'myUploader')
            }, function(){
            });
            return false;
        })

        init_upload_pic('myUploader','baidu_template_pic_show', {
            "data_list":[]});            // 当前的维修类型

        // 当前的客户地址

        //当前部门小组

    });
</script>
<!-- zui js -->
<script src="http://work.kefu.cunwo.net/dist/js/zui.min.js"></script>

<script >
    var PIC_DEL_URL = 'http://work.kefu.cunwo.net/api/huawu/upload/ajax_del';
</script>
<link href="http://work.kefu.cunwo.net/dist/lib/uploader/zui.uploader.min.css" rel="stylesheet">
<script src="http://work.kefu.cunwo.net/dist/lib/uploader/zui.uploader.min.js"></script>
<script>


</script>
<script src="http://work.kefu.cunwo.net/js/common/uploadpic.js"  type="text/javascript"></script>