<?php
namespace Mohuishou\Lib;

class Evaluate extends JwcBase{
    protected function spider(){
        //评教列表页面
        $url="http://202.115.47.141/jxpgXsAction.do?oper=listWj&page=";
        $rules=[

        ];
        echo 123;
        echo $html=$this->get($url);
//        QueryList::Query($html,$rules);
    }
}