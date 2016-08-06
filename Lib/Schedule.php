<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/6 0006
 * Time: 11:30
 */
namespace Mohuishou\Lib;
use QL\QueryList;

require_once "function.php";

class Schedule extends JwcBase{

    /**
     * 获取本学期课表
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    protected function spider()
    {
        $url = 'http://202.115.47.141/xkAction.do?actionType=6';
        //课程表的采集规则
        $rule = [
            'plan' => ['td:eq(0)', 'text'],//培养方案
            'courseId' => ['td:eq(1)', 'text'],//课程号
            'name' => ['td:eq(2)', 'text'],//课程名
            'lessonId' => ['td:eq(3)', 'text'],//课序号
            'credit' => ['td:eq(4)', 'text'],//学分
            'courseType' => ['td:eq(5)', 'text'],//课程属性
            'examType' => ['td:eq(6)', 'text'],//考试类型
            'teacher' => ['td:eq(7)', 'text'],//教师
            'studyWay' => ['td:eq(9)', 'text'],//修读方式
            'chooseType' => ['td:eq(10)', 'text'],//选课状态
            'allWeek' => ['td:eq(11)', 'text','','cbWeek'],//周次
            'day' => ['td:eq(12)', 'text'],//星期
            'session' => ['td:eq(13)', 'text','','cbWeek'],//节次
            'campus' => ['td:eq(14)', 'text'],//校区
            'building' => ['td:eq(15)', 'text'],//教学楼
            'classroom' => ['td:eq(16)', 'text'],//教室
            'callback' =>"removeSpace"
        ];

        $page=$this->get($url);
        $data=QueryList::Query($page,$rule,'#user:eq(1) tr','','',true)->data;

        //防止出现一门课多个上课时间的问题
        foreach($data as $key => $value){
            if(!empty($value['courseId'])){
                if($value['courseId']<10){
                    $a=$data[$key-1];
                    $a['allWeek']=cbWeek($value['plan']);
                    $a['week']=$value['courseId'];
                    $a['session']=cbWeek($value['name']);
                    $a['building']=$value['credit'];
                    $a['classroom']=$value['courseType'];
                    $data[$key]=$a;
                    $scheduleData[]=$a;
                }
            }else{
                unset($data[$key]);
            }
        }
        return $data;
    }

}