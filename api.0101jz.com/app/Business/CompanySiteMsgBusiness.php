<?php
// 工单
namespace App\Business;

use App\Models\Company;
use App\Models\CompanySiteMsg;
use App\Models\CompanyWork;
use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanySiteMsgBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanySiteMsg';


    /**
     * 获得消息列表
     *
     * @param int $company_id 公司id
     * @param int $staff_id 员工id
     * @param int $is_read 是否已读
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function getSiteMsgByIsRead($company_id, $staff_id, $is_read = 0){
        return CompanySiteMsg::select(['id', 'msg_name', 'mst_content', 'is_read', 'accept_staff_id'
            , 'operate_staff_id', 'created_at'])
            ->orderBy('id', 'desc')
            ->where([
                ['company_id', '=', $company_id],
                ['accept_staff_id', '=', $staff_id],
                ['is_read', '=', $is_read],
            ])->get();

    }
    /**
     * 获得消息列表
     *
     * @param object $workObj 工单对象
     * @param int $operate_staff_id 员工id null 用 $workObj 对象的; 0 系统发送消息
     * @param int $operate_staff_history_id 员工历史id null 用 $workObj 对象的; 0 系统发送消息
     * @param string $msg_name 标题
     * @param string $mst_content 消息内容
     * @return object Response
     * @author zouyan(305463219@qq.com)
     */
    public static function sendSiteMsg($workObj, $operate_staff_id = null , $operate_staff_history_id = null, $msg_name = '', $mst_content = ''){

        if(is_null($operate_staff_id)) $operate_staff_id = $workObj->operate_staff_id;
        if(is_null($operate_staff_history_id)) $operate_staff_history_id = $workObj->operate_staff_history_id;
        $data = [
            'company_id' => $workObj->company_id,// 公司ID
            'msg_name' => $msg_name,// 标题
            'mst_content' => $mst_content,// 消息内容
            'is_read' => 0,// 是否已读0未读;1已读
            'accept_staff_id' => $workObj->send_staff_id,// 接受员工id
            'accept_staff_history_id' => $workObj->send_staff_history_id,// 接受员工历史id
            'operate_staff_id' => $operate_staff_id,// 操作员工id
            'operate_staff_history_id' => $operate_staff_history_id,// 操作员工历史id
        ];
        $companySiteMsgObj = null;
        Common::getObjByModelName("CompanySiteMsg", $companySiteMsgObj);
        return Common::create($companySiteMsgObj, $data);
    }
}
