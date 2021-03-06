<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/6 0006
 * Time: 18:45
 */
namespace Mohuishou\Lib;

use QL\QueryList;

class Exam extends JwcBase{


    protected function spider()
    {
        $url="http://202.115.47.141/ksApCxAction.do?oper=getKsapXx";
        $rules=[
            'exam_name'=>['td:eq(0)','text'],
            'campus'=>['td:eq(1)','text'],
            'building'=>['td:eq(2)','text'],
            'classroom'=>['td:eq(3)','text'],
            'class_name'=>['td:eq(4)','text'],
            'week'=>['td:eq(5)','text'],
            'day'=>['td:eq(6)','text'],
            'date'=>['td:eq(7)','text'],
            'time'=>['td:eq(8)','text'],
            'seat'=>['td:eq(9)','text']

        ];
        $page=$this->get($url);
        $data=QueryList::Query($page,$rules,'#user:eq(1) tr')->data;

        //抓取的第一个数组一般为空，还是验证一下
        if(empty($data[0]['exam_name'])){
            array_shift($data); //将第一个空数组弹出
        }

        return $data;
    }
}