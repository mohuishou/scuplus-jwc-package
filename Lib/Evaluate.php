<?php
namespace Mohuishou\Lib;

use QL\QueryList;

class Evaluate extends JwcBase{
    protected function spider($page=1){
        //评教列表页面
        $url="http://202.115.47.141/jxpgXsAction.do?oper=listWj&page=".$page;
        $rules=[
            'param'=>['#user img','name','-td[align="right"]',function($str){
                if($str){
                    return explode('#@',$str);
                }
            }],
            'status'=>['#user img','onclick','',function($str){
                //判断是否已经评教
                if(trim($str)=="evaluation(this)"){
                    return 0;
                }else{
                    return 1;
                }
            }]

        ];

        $rules_page=[
            'page'=>['script:eq(3)','html','',function($page){
                $pattern="/document\.all\.pageNo\.value>(.)/";
                preg_match($pattern,$page,$no);
                if($no[1]){
                    return $no[1];
                }
            }]
        ];
        $html=$this->get($url);
        $data_info=QueryList::Query($html,$rules)->data;
        $data['info']=$data_info;
        $data_page=QueryList::Query($html,$rules_page)->data;
        $data['page']=$data_page[0];
        return $data;
    }
}