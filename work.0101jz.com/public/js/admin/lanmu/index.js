
var SUBMIT_FORM = true;//防止多次点击提交
$(function(){
    // $('.search_frm').trigger("click");// 触发搜索事件
    ajax_status_count(0, 0, 0);//ajax工单状态统计
    // reset_list(false, true);
    // 自动更新数据
    var autoObj = new Object();
    autoObj.orderProcessList = function(){
        ajax_status_count(1, 0, 0);//ajax工单状态统计
    };
    setInterval(autoObj.orderProcessList,60000);

    $('.qrcode').each(function(){
        let qrObj = $(this);
        // 交付二维码
        var qrcodeurl = qrObj.data('qrcodeurl');
        consoleLogs(['qrcodeurl', qrcodeurl]);
        if(qrcodeurl != ''){
            // showQRCodeTable('qrcode', qrcodeurl, 250, 250);// 显示付款二维码
            // qrObj.qrcode(toUtf8QRCode(qrcodeurl)); //任意字符串
            qrObj.qrcode({
                render: "canvas", //table方式
                width: 150, //宽度
                height:150, //高度
                text: toUtf8QRCode(qrcodeurl) //任意内容
            });
        }
    });
});

//业务逻辑部分
var otheraction = {

    copyWebUrl :function(thisObj) {
        var btnObj = $(thisObj);
        var htmlStr = btnObj.closest('.web_block').find('.web_url').text();
        console.log('==htmlStr===', htmlStr);
        copyToClip( thisObj, htmlStr);
        // Clipboard.copy(thisObj,htmlStr,'复制成功！！');
    },
    copyH5Url:function(thisObj) {
        var btnObj = $(thisObj);
        var htmlStr = btnObj.closest('.m_block').find('.h5_url').text();
        console.log('==htmlStr===', htmlStr);
        copyToClip( thisObj, htmlStr);
        // Clipboard.copy(thisObj,htmlStr,'复制成功！！');
    }

};

//ajax工单状态统计
// from_id 来源 0 页面第一次加载,不播放音乐 1 每分钟获得数量，有变化，播放音乐

function ajax_status_count(from_id ,staff_id, operate_staff_id){
    // if (!SUBMIT_FORM) return false;//false，则返回

    // 验证通过
    // SUBMIT_FORM = false;//标记为已经提交过
    var data = {
        'staff_id': staff_id,
        'operate_staff_id': operate_staff_id,
    };
    console.log(SATUS_COUNT_URL);
    console.log(data);
    if( from_id == 0)  var layer_count_index = layer.load();
    $.ajax({
        'type' : 'POST',
        'url' : SATUS_COUNT_URL,
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
                var doStatus = NEED_PLAY_STATUS;
                var doStatusArr = doStatus.split(',');

                // 遍历
                var needPlay = false;// true：播放；false:不播放
                var selected_status = $('select[name=status]').val();
                for(var temStatus in statusCount){//遍历json对象的每个key/value对,p为key
                    var countObj = $(".status_count_" + temStatus );
                    if(countObj.length <= 0) continue;
                    var temCount = statusCount[temStatus];
                    var oldCount = countObj.data('old_count');
                    console.log(oldCount);
                    console.log(temCount);
                    if(oldCount != temCount){
                        countObj.html(temCount);
                        countObj.data('old_count', temCount);
                        console.log('new_order');
                        // 数量变大了
                        if( from_id == 1 && (!needPlay) && temCount > oldCount  && doStatusArr.indexOf(temStatus) >= 0){
                            needPlay = true;
                        }

                        // 刷新列表-当前页
                        if( from_id == 1 && selected_status == temStatus){
                            // console.log('刷新列表-当前页');
                            // reset_list(true, true);
                        }
                    }
                }
                if(needPlay && from_id == 1){// 播放
                    console.log('播放提示音');
                    run_sound('new_order');
                }

                // status_count_
            }
            // SUBMIT_FORM = true;//标记为未提交过
            if( from_id == 0)   layer.close(layer_count_index);//手动关闭
        }
    });
    return false;
}
