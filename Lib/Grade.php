<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/5 0005
 * Time: 17:43
 */
namespace Mohuishou\Lib;
use QL\QueryList;

class Grade extends JwcBase{


    /**
     * 抓取所有成绩
     * @author mohuishou<1@lailin.xyz>
     * @return array
     * @throws \Exception
     */
    protected function spider()
    {
        //抓取所有及格成绩
        $url_pass_grade='http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=qbinfo&lnxndm';
        $page=$this->get($url_pass_grade);
        $page='<!DOCTYPE HTML><html><head></head>'.$page.'</html>';

        //获取学期年份
        $term=QueryList::Query($page,[
            'term'=>['b','text']
        ])->data;

        //获取过了的成绩
        $rule=[
            'courseId'=>['td:eq(0)','text'],
            'lessonId'=>['td:eq(1)','text'],
            'name'=>['td:eq(2)','text'],
            'enName'=>['td:eq(3)','text'],
            'credit'=>['td:eq(4)','text'],
            'courseType'=>['td:eq(5)','text'],
            'grade'=>['td:eq(6)','text'],
        ];
        $pass=QueryList::Query($page,$rule,'#user tr')->data;

        //数据整理
        $grade_pass=[];
        $i=-1;
        foreach($pass as $k => $v){
            if(empty($v['courseId'])){
                $i++;
                continue;
            }
            $v['termId']=$i+1;
            $v['term']=$term[$i]['term'];
            $grade_pass[$i][]=$v;
        }
        $grade=$grade_pass;

        //抓取当前不及格成绩，并将当前不及格成绩插入本学期
        $url_fail_grade='http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=bjg';
        $page=$this->get($url_fail_grade);

        $fail=QueryList::Query($page,$rule,'#user:eq(0) tr',"UTF-8")->data;
        foreach($fail as $k => $v){
            if(!empty($v['courseId'])){
                $v['term']=$term[$i]['term'];
                $v['termId']=$i+1;
                $grade[$i]=$v;
            }
        }

        return $grade;
    }


    /**
     * 获得本学期成绩
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     * @throws \Exception
     */
    public function getThisTermGrade(){
        return $this->login()->spiderThisTermGrade();
    }

    /**
     * 抓取本学期成绩
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     * @throws \Exception
     */
    protected function spiderThisTermGrade(){
        $url_now="http://202.115.47.141/bxqcjcxAction.do";
        $page=$this->get($url_now);
        $rules=[
            'courseId'=>['td:eq(0)','text'],
            'lessonId'=>['td:eq(1)','text'],
            'name'=>['td:eq(2)','text'],
            'enName'=>['td:eq(3)','text'],
            'credit'=>['td:eq(4)','text'],
            'courseType'=>['td:eq(5)','text'],
            'grade'=>['td:eq(6)','text'],
        ];
        $grade_now=QueryList::Query($page,$rules,'#user tr')->data;

        //抓取的第一个数组一般为空，还是验证一下
        if(empty($grade_now[0]['courseId'])){
            array_shift($grade_now); //将第一个空数组弹出
        }
        return $grade_now;
    }
}