<?php


/**
 * 成功提示
 *
 * @param array $data
 * @param string $dataName
 *
 * @return \Illuminate\Http\JsonResponse
 */
function success($data = [], $dataName = 'data', $message = 'success')
{
    return response()->json(['message' => $message, 'code' => 1, "{$dataName}" => $data], 200);
}

function error($msg = '未知错误', $code = -1, $httpStatus = 422)
{
    return response()->json(['errmsg' => $msg, 'code' => $code, 'data' => ''], $httpStatus);
}

function getrandstr(){
    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);//打乱字符串
    $rands= substr($randStr,0,6);//substr(string,start,length);返回字符串的一部分
    return $rands;
}

if (!function_exists('is_route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string $name
     * @return bool
     */
    function is_route($name)
    {
        try {
            route($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

/*
* 成功或者失败的提示
*/
function flash_message(string $message = '成功', bool $success = true)
{
    $className = $success ? 'alert-success' : 'alert-danger';
    session()->flash('alert-message', $message);
    session()->flash('alert-class', $className);
}
// 数字货币转化
function handleStr($intStr, $per = true, $decStr = 0, $len = 0, $rep = ',')
{
    $intStr = $per ? sprintf("%.2f",$intStr): $intStr;
    $countStr = count(explode('.', $intStr));
    if ($countStr > 2)
        return $intStr;

    if ($countStr == 2)
        list($intStr,$decStr) = explode('.', $intStr);

    $str = strrev($intStr);
    $lenStr = strlen($intStr) + 1;
    if ($lenStr == 4)
        return$intStr;

    for ($i = 0; $i < $lenStr; $i++) {
        if (($i + 1) % 4 === 0) {
            $str = substr_replace($str, $rep, $i, $len);
        }
    }
    $str = strrev($str);

    return $per ? $str . '.' . $decStr : $str;

}

function getDiffDateRange($startdate = null, $enddate = null)
{
    date_default_timezone_set("Asia/Shanghai");

    $stimestamp = strtotime($startdate);
    $etimestamp = $enddate ? strtotime($enddate) : strtotime(date('Y-m-d')) ;

    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;

    // 保存每天日期
    $date = array();

    for($i=0; $i<$days; $i++){
        $date[] = date('Y-m-d', $stimestamp+(86400*$i));
    }

    return $date;
}
