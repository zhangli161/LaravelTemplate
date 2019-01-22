<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/20
 * Time: 11:17
 */

/**
 * 获取介于start_date_str与end_date_str之间的所有日期
 * @param string $start_date_str
 * @param string $end_date_str
 * @param int $type 日期类型 0日 1周 2月 3季度 4年
 * @param string $format
 * @return array
 */
function getDatesBetween($start_date_str, $end_date_str, int $type = 0, $format = null)
{
    if (empty($start_date_str) or empty($end_date_str))
        return [];
    $ret = array();
    $start_date = strtotime($start_date_str);
    $end_date = strtotime($end_date_str);
    $t_timestemp = $start_date;
    while ($t_timestemp <= $end_date) {
        switch ($type) {
            case 0:
                array_push($ret, date($format ? $format : 'Y-m-d', $t_timestemp));
                $t_timestemp = strtotime(" next day", $t_timestemp);
                break;
            case 1:
                $date1 = date($format ? $format : 'Y-m-d', $t_timestemp);
                $t_timestemp = strtotime($date1 . "next Monday");//下一个周一
                $date2 = date($format ? $format : 'Y-m-d',
                    $t_timestemp > $end_date ?          //如果结束时间在下一个周一前，那么date2是结束时间，否则是下个周日
                        $end_date : strtotime($date1 . " next Sunday"));
                array_push($ret, $date1 == $date2 ? $date1 : "$date1 - $date2");
                break;
            case 2:
                array_push($ret, date($format ? $format : 'Y-m', $t_timestemp));
                $t_timestemp = strtotime(" next month", $t_timestemp);
                break;
            case 3:
                $date1 = date($format ? $format : 'Y-m', $t_timestemp);
                $season = ceil((date('n', $t_timestemp)) / 3);//当月是第几季度
                $season_start_timestemp = mktime(0, 0, 0, $season * 3 - 2, '1', date("Y", $t_timestemp));//本季度第一个月1日
                $season_end_timestemp = mktime(0, 0, 0, $season * 3, '1', date("Y", $t_timestemp));//本季度最后一个月1日
                $t_timestemp_next = mktime(0, 0, 0, $season * 3 + 1, '1', date("Y", $t_timestemp));//下个季度第一个月1日
                $date1 = date($format ? $format : 'Y-m-d', max($season_start_timestemp, $t_timestemp));
                $date2 = date($format ? $format : 'Y-m-d', $t_timestemp_next > $end_date ?
                    $end_date : $season_end_timestemp);

                array_push($ret, $date1 == $date2 ? $date1 : "$date1 - $date2");
                $t_timestemp = $t_timestemp_next;
                break;
            case 4:
                array_push($ret, date($format ? $format : 'Y', $t_timestemp));
                $t_timestemp = strtotime(" next year", $t_timestemp);
                break;
            default:
                break 2;
        }
    }

    return $ret;
}

function getRealImageUrl($url)
{
    if (!$url) {
        return '';
    }
    $pattern = array('/http:\/\//', '/https:\/\//');
    $result = preg_match_all($pattern[0], $url, $m) || preg_match_all($pattern[1], $url, $m);
    if (!$result) {
        $url =
            \Illuminate\Support\Facades\Storage::disk('admin')->url($url);
    }
    return $url;
}


function get_server_ip()
{
    if (isset($_SERVER['SERVER_NAME'])) {
        return gethostbyname($_SERVER['SERVER_NAME']);
    } else {
        if (isset($_SERVER)) {
            if (isset($_SERVER['SERVER_ADDR'])) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } elseif (isset($_SERVER['LOCAL_ADDR'])) {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip ? $server_ip : '获取不到服务器IP';
    }
}