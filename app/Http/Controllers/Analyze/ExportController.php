<?php


namespace App\Http\Controllers\Analyze;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController
{
    public function serverMessage()
    {
//        // 获取本周开始时间和结束时间
//        $start_time = Carbon::today();
//        $end_time = $request->input('et');
//        $fList = array();
//        if (empty($start_time) || empty($end_time)){
//            return error('非法请求', 500);
//        }
//        // 如果结束时间小于，则状态为固定，并查询表中存在的数据
//        if ($end_time > date('Y-m-d H:i:s')){
//            return error('本周数据暂未完全生成', 500);
//        }
//        // 如果结束时间小于本周第一天，则判断为上周之前的数据
//        if ($end_time < getCurrentWeek('start')){
//            $userProduct->checkInviteLog($start_time, $end_time);
//        }
//
//        $inviteList = $userProduct->getInviteData($start_time, $end_time);
//        if (empty($inviteList['QP'])){
//            return error('暂无数据', 500);
//        }
//        $fList['FN'] = $userProduct->getOilFailure(0);
//        $fList['OF'] = $userProduct->getOilFailure([1,5]);
//        $fList['ST'] = $userProduct->getOilFailure([6,10]);
//        $fList['TF'] = $userProduct->getOilFailure([11,14]);
//        $fList['TC'] = '统计时间段：'.$start_time.'-'.$end_time;
//        $cellData = array_merge($inviteList,$fList);
//
//        return Excel::create('每日推送数据'.$end_time,function($excel) use ($cellData){
//
//            $excel->sheet('好友助力', function($sheet) use ($cellData){
//                $tot = count($cellData) ;
//                //设置单元格宽度、字体大小
//                $sheet->setWidth(array(
//                    'A'     =>  18,
//                    'B'     =>  12,
//                    'C'     =>  12,
//                    'D'     =>  14,
//                    'E'     =>  22,
//                    'F'     =>  8,
//                    'G'     =>  8,
//                    'H'     =>  8
//                ))->rows($cellData)->setFontSize(12);
//
//                // 菜单 样式
//                $sheet->setHeight(1, 30);
//                $sheet->setBorder('A1:H3', 'thin');
//                $sheet->setBorder('A5:E8', 'thin');
//                //合并行
//                $sheet->mergeCells('A1:H1');
//                $sheet->mergeCells('A5:E5');
//                $sheet->mergeCells('A6:D6');
//                $sheet->mergeCells('E6:E7');
//
//                $sheet->cells('A1:H1', function($cells) {
//                    $cells->setAlignment('center');
//                    $cells->setValignment('center');
//                    $cells->setFontWeight('bold');
//                });
//
//                // 高亮显示
//                $sheet->cells('A2:H2', function($cells) {
//                    $cells->setBackground('#8ea9db');
//                    $cells->setFontWeight('bold');
//                });
//                $sheet->cells('A5:E5', function($cells) {
//                    $cells->setBackground('#8ea9db');
//                    $cells->setAlignment('center');
//                    $cells->setFontWeight('bold');
//                });
//                $sheet->cells('A6:D6', function($cells) {
//                    $cells->setBackground('#a9d08e');
//                    $cells->setAlignment('center');
//                    $cells->setFontWeight('bold');
//                });
//                $sheet->cells('E6:E7', function($cells) {
//                    $cells->setBackground('#a9d08e');
//                    $cells->setAlignment('center');
//                    $cells->setValignment('center');
//                    $cells->setFontWeight('bold');
//                });
//
//                //填充每个单元格的内容
//                $sheet->cell('A1',$cellData['TC']);
//                $sheet->cell('A2','获得参与资格人数');
//                $sheet->cell('B2','参与人数');
//                $sheet->cell('C2','成功人数');
//                $sheet->cell('D2','失败人数');
//                $sheet->cell('E2','新增用户');
//                $sheet->cell('F2','参与率');
//                $sheet->cell('G2','成功率');
//                $sheet->cell('H2','失败率');
//                $sheet->cell('A3',$cellData['QP']);
//                $sheet->cell('B3',$cellData['PP']);
//                $sheet->cell('C3',$cellData['NS']);
//                $sheet->cell('D3',$cellData['NF']);
//                $sheet->cell('E3',$cellData['NA']);
//                $sheet->cell('F3',round(empty($cellData['QP'])?0:($cellData['PP'] / $cellData['QP']) * 100 , 2). '%');
//                $sheet->cell('G3',round(empty($cellData['PP'])?0:($cellData['NS'] / $cellData['PP']) * 100, 2). '%');
//                $sheet->cell('H3',round(empty($cellData['PP'])?0:($cellData['NF'] / $cellData['PP']) * 100, 2). '%');
//
//                $sheet->cell('A5','失败人数');
//                $sheet->cell('A6','备油成功但人数未达标');
//                $sheet->cell('A7','助力0人');
//                $sheet->cell('B7','助力1-5人');
//                $sheet->cell('C7','助力6-10人');
//                $sheet->cell('D7','助力10人以上');
//                $sheet->cell('E6','备油失败但人数达标');
//                $sheet->cell('A8',$cellData['FN']);
//                $sheet->cell('B8',$cellData['OF']);
//                $sheet->cell('C8',$cellData['ST']);
//                $sheet->cell('D8',$cellData['TF']);
//                $sheet->cell('E8',$cellData['NF'] - $cellData['FN'] - $cellData['OF'] - $cellData['ST'] - $cellData['TF']);
//            });
//
//        })->export('xls');
    }
}
