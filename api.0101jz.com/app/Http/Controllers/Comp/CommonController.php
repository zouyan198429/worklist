<?php

namespace App\Http\Controllers\Comp;

use App\Exceptions\ExportException;
use App\Models\Company;
use App\Models\CompanyProRecord;
use App\Models\CompanyProUnit;
use App\Models\CompanyVisitCount;
use App\Models\CompanyVisitUnique;
use App\Models\SiteConfig;
use App\Models\SiteIntro;
use App\Models\SiteNews;
use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController;
use Illuminate\Support\Facades\DB;

class CommonController extends CompController
{
    /**
     * 获得首页接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // 日志总量
        $recordCount = CompanyProRecord::where([
            ['company_id', '=', $company_id],
        ])->count();
        // 生产单元
        $unitCount = CompanyProUnit::where([
            ['company_id', '=', $company_id],
        ])->whereIn('status', [1])->count();
        // 微站访问
        $visitCount = CompanyVisitCount::where([
            ['company_id', '=', $company_id],
        ])->sum('visit_amount');
        // 用户总量
        $visitUniqueCount = CompanyVisitUnique::where([
            ['company_id', '=', $company_id],
        ])->count();
        // 平台公告
        $newList = SiteNews::select(['id','new_title','updated_at'])->limit(10)->orderBy('id', 'desc')->get();
        // 平台信息
        $configArr = SiteConfig::get();
        $configList = [];
        foreach($configArr as $v){
            $configList[$v['id']] = $v;
        }
        // 获得帮助单条信息
        $siteIntro = SiteIntro::select(['id','intro_title','created_at','updated_at'])
            ->limit(10)
            ->orderBy('intro_sort', 'desc')->orderBy('id', 'desc')->get();

        return okArray([
            'recordCount' => $recordCount,// 日志总量
            'unitCount' => $unitCount,// 生产单元
            'visitCount' => $visitCount,// 微站访问
            'visitUniqueCount' => $visitUniqueCount,// 用户总量
            'newList' => $newList,// 平台公告
            'configArr' => $configList,// 平台信息
            'siteIntro' => $siteIntro,// 帮助单条信息
        ]);
    }
    /**
     * 获得首页接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function admin(Request $request)
    {
        $this->InitParams($request);
        // 会员总数
        $companyCount = Company::count();
        // 今日注册
        $begin_time  = judge_date(day_format_time(1,'',0), 'Y-m-d H:i:s');
        $end_time  = judge_date(day_format_time(2,'',0) - 1, 'Y-m-d H:i:s');
        $todayRegCount = Company::whereBetween('created_at', [$begin_time, $end_time])->count();
        // 今日日志
        $todayRecordCount = CompanyProRecord::whereBetween('created_at', [$begin_time, $end_time])->count();
        // 生产单元
        $unitWaitCount = CompanyProUnit::where([
            ['status', '=', 0],
        ])->count();
        // 最新注册会员
        $newRegList = Company::select(['id','company_name','company_linkman','company_mobile','created_at','updated_at'])
            ->limit(10)
            ->orderBy('id', 'desc')->get();
        return okArray([
            'companyCount' => $companyCount,// 会员总数
            'todayRegCount' => $todayRegCount,// 今日注册
            'todayRecordCount' => $todayRecordCount,// 今日日志
            'unitWaitCount' => $unitWaitCount,// 生产单元
            'newRegList' => $newRegList,// 最新注册会员
        ]);

    }

    /**
     * 获得所有列表接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function all(Request $request)
    {
        $this->InitParams($request);
        return Common::requestAllDataByModel($request);
    }

    /**
     * 获得列表接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);

        return Common::requestListDataByModel($request);
    }

    /**
     * 根据id获得详情
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string $relations 关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function getInfo(Request $request)
    {
        $this->InitParams($request);
        return Common::requestInfoByID($request);
    }

    /**
     * 新加接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        return Common::requestCreate($request);
    }

    /**
     * 批量新加接口-data只能返回成功true:失败:false
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function addBath(Request $request)
    {
        $this->InitParams($request);
        return Common::requestCreateBath($request);
    }

    /**
     * 批量新加接口-data只能返回成功true:失败:false
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function addBathByPrimaryKey(Request $request)
    {
        $this->InitParams($request);
        return Common::requestCreateBathByPrimaryKey($request);
    }
    /**
     * 修改接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @param string $queryParams 条件数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function save(Request $request)
    {
        $this->InitParams($request);
        return Common::requestUpdate($request);
    }


    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveBathById(Request $request)
    {
        $this->InitParams($request);
        return Common::batchSaveByPrimaryKey($request);
    }

    /**
     * 通过id修改接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @param string $queryParams 条件数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveById(Request $request)
    {
        $this->InitParams($request);
        return Common::requestSave($request);
    }

    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string incDecType 增减类型 inc 增 ;dec 减[默认]
     * @param string incDecField 增减字段
     * @param string incDecVal 增减值
     * @param string modifFields 修改的其它字段 -没有，则传空数组json
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveDecIncByQuery(Request $request)
    {
        $this->InitParams($request);
        return Common::requestSaveDecIncByQuery($request);
    }

    /**
     * 自增自减接口,通过数组[二维]-data操作的行数数组
     *
     * @param int $company_id 公司id
     * @param string $dataParams 条件数组/json字符
        $dataParams = [
            [
                'Model_name' => 'model名称',
                'primaryVal' => '主键字段值',
                'incDecType' => '增减类型 inc 增 ;dec 减[默认]',
                'incDecField' => '增减字段',
                'incDecVal' => '增减值',
                'modifFields' => '修改的其它字段 -没有，则传空数组',
            ],
        ];
     如:
    [
        [
            'Model_name' => 'CompanyProSecurityLabel',
            'primaryVal' => '7',
            'incDecType' => 'inc',
            'incDecField' => 'validate_num',
            'incDecVal' => '2',
            'modifFields' => [],
        ],
        [
            'Model_name' => 'CompanyProSecurityLabel',
            'primaryVal' => '9',
            'incDecType' => 'inc',
            'incDecField' => 'validate_num',
            'incDecVal' => '1',
            'modifFields' => [
                'status' => 1,
            ],
        ],
    ];
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveDecIncByArr(Request $request)
    {
        $this->InitParams($request);
        return Common::batchSaveDecIncByPrimaryKey($request);
    }
    /**
     * 根据条件删除接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function del(Request $request)
    {
        $this->InitParams($request);

        return Common::requestDel($request);
    }

    /**
     * 同步修改关系接口
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string/array $synces 同步关系数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function sync(Request $request)
    {
        $this->InitParams($request);

        return Common::requestSync($request);
    }

    /**
     * 移除关系接口
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string/array $detaches 移除关系数组/json字符 空：移除所有，id数组：移除指定的
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function detach(Request $request)
    {
        $this->InitParams($request);

        return Common::requestDetach($request);
    }

}
