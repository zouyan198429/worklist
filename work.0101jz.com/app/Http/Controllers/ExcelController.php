<?php

namespace App\Http\Controllers;

use App\Services\Excel\ChunkReadFilter;
use App\Services\Excel\ImportExport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

// use Maatwebsite\Excel\Excel;


class ExcelController extends Controller
{
//    //Excel文件导出功能 By Laravel学院
//    public function export(){
//        $cellData = [
//            ['编号','姓名','绩效','电话号码'],
//            ['10001','AAAAA','99','150-xxxx-xxxx'],
//            ['10002','BBBBB','92','137-xxxx-xxxx'],
//            ['10003','CCCCC','95','157-xxxx-xxxx'],
//            ['10004','DDDDD','89','177-xxxx-xxxx'],
//            ['10005','EEEEE','96','188-xxxx-xxxx'],
//            ['10006','FFFFF','96','180-xxxx-xxxx'],
//            ['10007','ggggg','96','181-xxxx-xxxx'],
//            ['10008','HHHHH','96','182-xxxx-xxxx'],
//        ];
//
//        /*
//         * 如果你要导出csv或者xlsx文件，只需将 export 方法中的参数改成csv或xlsx即可。
//         * 如果还要将该Excel文件保存到服务器上，可以使用 store 方法：
//         */
//        Excel::create(iconv('UTF-8', 'GBK', '模板文件'),function($excel) use ($cellData){
//            $excel->sheet('score', function($sheet) use ($cellData){
//                $sheet->rows($cellData);
//            });
//        })->store('xls')->export('xls');
//    }



    // 导入
    public function import(){
        $fileName = 'students.xlsx';
        $dataStartRow = 1;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
        $headRowNum = 1;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
        $headArr = [
            '姓名' => 'name',
            '语文' => 'chinese',
            '数学' => 'maths',
            '外语' => 'english',
        ];
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        $dataArr = ImportExport::import($fileName, $dataStartRow, $headRowNum, $headArr);
        pr($dataArr);
    }

    //导出
    public function export(){
        $dataJson = '[{"name":"\u738b\u4e8c\u5c0f","chinese":"82","maths":"78","english":"65"},{"name":"\u674e\u4e07\u8c6a","chinese":"68","maths":"87","english":"79"},{"name":"\u5f20\u4e09\u4e30","chinese":"89","maths":"90","english":"98"},{"name":"\u738b\u8001\u4e94","chinese":"68","maths":"81","english":"72"}]';
        $dataArr = json_decode($dataJson, true);
        $headArr = ['姓名', '语文', '数学', '外语'];
        ImportExport::export('','学生成绩表',$dataArr,1, $headArr, 1, ['sheet_title' => '学生成绩表']);

    }
}
