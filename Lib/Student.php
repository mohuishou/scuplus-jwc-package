<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/6 0006
 * Time: 15:53
 */
namespace Mohuishou\Lib;

use QL\QueryList;

class Student extends JwcBase{
    protected function spider()
    {
        $url="http://202.115.47.141/xjInfoAction.do?oper=xjxx";
        $page=$this->get($url);
        $rules=[
            "name"=>['tr:eq(0) td:eq(3)','text'], //姓名
            "en_name"=>['tr:eq(1) td:eq(3)','text'], //英文名
            "id"=>['tr:eq(2) td:eq(3)','text'], //身份证号
            "sex"=>['tr:eq(3) td:eq(1)','text'], //性别
            "type"=>['tr:eq(3) td:eq(3)','text'], //学生类别：本科或者其他
            "status"=>['tr:eq(4) td:eq(3)','text'], //学生状态：在校
            "nation"=>['tr:eq(5) td:eq(3)','text'], //民族
            "native"=>['tr:eq(6) td:eq(1)','text'], //籍贯
            "birth"=>['tr:eq(6) td:eq(3)','text'], //生日
            "political"=>['tr:eq(7) td:eq(1)','text'], //政治面貌：共青团员等
            "college"=>['tr:eq(12) td:eq(3)','text'], //学院
            "major"=>['tr:eq(13) td:eq(1)','text'], //专业
            "year"=>['tr:eq(14) td:eq(1)','text'], //年级
            "class"=>['tr:eq(14) td:eq(3)','text'], //班级
            "campus"=>['tr:eq(16) td:eq(1)','text'], //校区

        ];

        $data=QueryList::Query($page,$rules,'#tblView:eq(0)','','',true)->data;

        return $data[0];
    }
}