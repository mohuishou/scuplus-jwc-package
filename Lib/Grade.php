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

    public function spider()
    {
        //抓取所有及格成绩
        $url_all_grade='http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=qbinfo&lnxndm';
        $page=$this->_curl->get($url_all_grade);

        if ( $this->_curl->error) {
            throw new \Exception('Error: ' .  $this->_curl->errorCode . ': ' .  $this->_curl->errorMessage);
        }
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        $page='<!DOCTYPE HTML><html><head></head>'.$page.'</html>';

        $term=QueryList::Query($page,[
            'term'=>['b','text']
        ])->data;
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

        /*--------------数据整理---------------*/
        $b=[];
        $i=-1;
        foreach($pass as $k => $v){
            if(empty($v['courseId'])){
                $i++;
                continue;
            }
            $v['termId']=$i+1;
            $v['term']=$term[$i]['term'];
            $b[$i][]=$v;
        }

        $gradeData=$b;

        #抓取当前不及格成绩，并将当前不及格成绩插入本学期

        $url2='http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=bjg';
        $page=$this->_curl->get($url2);
        if ( $this->_curl->error) {
            throw new \Exception('Error: ' .  $this->_curl->errorCode . ': ' .  $this->_curl->errorMessage);
        }
        $loss=QueryList::Query($page,$rule,'#user:eq(0) tr',"UTF-8")->data;
        foreach($loss as $k => $v){
            if(!empty($v['courseId'])){
                $v['term']=$term[$i]['term'];
                $v['termId']=$i+1;
                $gradeData[$i]=$v;
            }
        }



        return $gradeData;
    }
}