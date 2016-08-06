<?php
/**
 * 公共函数，主要是一些页面抓取的时候需要回调处理一下
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/6 0006
 * Time: 12:02
 */



/**
 * 去除教务处的换行符
 * @author mohuishou<1@lailin.xyz>
 * @param $str
 * @return mixed
 */
function removeSpace($str){
    $str=str_replace(chr(0xC2).chr(0xA0),"",$str);
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);
}

/**
 * 抓取的周次
 * @author mohuishou<1@lailin.xyz>
 * @param $str
 * @return string
 */
function cbWeek($str)
{
    $data='';


    //匹配数字
    preg_match_all('/[1-9]\d*/', $str, $res);
    $res=$res[0];

    $count_res=count($res);
    if($count_res==2){
        for ($i=$res[0];$i<=$res[1];$i++){
            if($i==$res[1]){
                $data .=$i;
            }else{
                $data .=$i.',';
            }
        }
    }elseif ($count_res>2){
        $data=implode(',',$res);
    }elseif($count_res==0){
        $data_one='1,3,5,7,9,11,13,15,17';//单周上课
        $data_two='2,4,6,8,10,12,14,16,18';//双周上课

        //单周
        preg_match_all('/单/', $str, $res_one);
        if(!empty($res_one[0])){
            return $data_one;
        }

        //双周
        preg_match_all('/双/', $str, $res_two);
        if(!empty($res_two[0])){
            return $data_two;
        }
    }

    return $data;
}

/**
 * 课程教师回调，针对有多个教师的情况
 * @author mohuishou<1@lailin.xyz>
 * @param $str
 * @return mixed
 */
function cbTeacher($str){
    $str=str_replace("*",'',$str);
    $str=str_replace(" ",',',$str);
    $str=removeSpace($str);
    return $str;
}
