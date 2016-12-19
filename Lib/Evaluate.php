<?php
namespace Mohuishou\Lib;

use QL\QueryList;

class Evaluate extends JwcBase{
    /**
     * 抓取评教页面
     * @author mohuishou<1@lailin.xyz>
     * @param int $page
     * @return mixed
     */
    protected function spider($page=1){
        //评教列表页面
        $url="http://202.115.47.141/jxpgXsAction.do?oper=listWj&pageSize=60";
        $html=$this->get($url);
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
        $rules_verify=[
            'value'=>['input[type="hidden"]:last','value'],
            'name'=>['input[type="hidden"]:last','name']
        ];

        $data['info']=QueryList::Query($html,$rules)->getData(function ($item){
            $param=['jwc_evaluate_id','jwc_teacher_id','teacher_name','teacher_type','course_name','course_id'];
            $data=[];
            foreach ( $item['param'] as $k=> $v) {
                $data[$param[$k]]=$v;
                if($k==3){
                    strstr($v,'学生') ? $teacher_type=1 : $teacher_type=0;
                    $data[$param[$k]]=$teacher_type;
                    $data['evaluate_type']=$v;
                }
            }
            $data['status']=$item['status'];
            return $data;
        });
        $data_verify=QueryList::Query($html,$rules_verify)->data;
        $data['verify']=$data_verify[0];
        return $data;
    }

    /**
     * 获取已经评教的信息
     * @author mohuishou<1@lailin.xyz>
     * @param int $page 页码
     * @return mixed
     */
    public function getEvaluateData($params){
        if($params['status']!=1){
            return false;
        }
        //登录教务处
        $this->login();
        $post_params=$this->evaluateFormParams($params);
        $post_params['oper']='wjResultShow';
        $formUrl="http://202.115.47.141/jxpgXsAction.do";//评教表单页面
        //抓取表单页面
        $url=$formUrl.'?'.http_build_query($post_params);
        $html=$this->get($url);
//        return $html;
        $rules=[
           'comment'=>['textarea[name="zgpj"]','text']
        ];
        $res=QueryList::Query($html,$rules)->data;
        return $res;
    }

    /**
     * 评教
     * @author mohuishou<1@lailin.xyz>
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function evaluate($params){
        //参数检测
        $param_verify=['jwc_evaluate_id','jwc_teacher_id','teacher_name','teacher_type','course_name','course_id','verify_name','verify_value','star','comment'];
        foreach ($param_verify as $v){
            if(!array_key_exists($v, $params)){
                throw new \Exception('参数错误！'.$v.'必须','4221');
            }
        }

        //登录教务处
        $this->login();

        //构造评教参数
        $params_data=$this->evaluateParams($params);

        //提交
        $url='http://202.115.47.141/jxpgXsAction.do?'.http_build_query($params_data);
        $html=$this->get($url);
        //检测评教是否成功
        $res=QueryList::Query($html,['info'=>['script','html','',function($a){
            $pattern='/alert\("(.+)"\)/';
            preg_match_all($pattern,$a,$res);
            $success='评估成功！';
            if($res[1][0]==$success){
                $a=[
                    'message'=>$res[1][0],
                    'status'=>1
                ];
            }else{
                $a=[
                    'message'=>$res[1][0],
                    'status'=>0
                ];
            }
            return $a;
        }]])->data;
        return $res[0]['info'];
    }

    /**
     * 生成评教需要的参数
     * @author mohuishou<1@lailin.xyz>
     * @param $params
     * @return array
     */
    protected function evaluateParams($params){
        $params_data=[];


        $post_params=$this->evaluateFormParams($params);
        $formUrl="http://202.115.47.141/jxpgXsAction.do";//评教表单页面
        //抓取表单页面
        $url=$formUrl.'?'.http_build_query($post_params);
        $html=$this->get($url);
        $res=QueryList::Query($html,['name'=>['[value=10_1]','name']])->data;
        //评分转换为需要的
        $star_teacher=[0.2,0.6,0.7,0.8,1];//教师评分表
        $star_teacher_zj=[0,0.3,0.6,0.8,1];//助教评分表
        if($params['teacher_type']==1){
            $star='10_'.$star_teacher[$params['star']-1];
        }else{
            $star='10_'.$star_teacher_zj[$params['star']-1];
        }
        foreach ($res as $v){
            $params_data[$v['name']]=$star;
        }

        $rules_verify=[
            'value'=>['input[type="hidden"]:last','value'],
            'name'=>['input[type="hidden"]:last','name']
        ];
        $data_verify=QueryList::Query($html,$rules_verify)->data;
        $params_data[$data_verify[0]['name']]=$data_verify[0]['value'];

        $params_data['wjbm']=$params['jwc_evaluate_id']; //评教id
        $params_data['bpr']=$params['jwc_teacher_id']; //教师id
        $params_data['pgnr']=$params['course_id']; //课程id
        $params_data['zgpj']=$this->utf82gbk($params['comment']);
        $params_data['oper']='wjpg';
        return $params_data;
    }

    protected function evaluateFormParams($params){
        //问卷列表页面
        $url="http://202.115.47.141/jxpgXsAction.do?oper=listWj&pageSize=60";
        $this->get($url);

        //首先抓取表单页面，构造需要评教的参数
        $post_params['wjbm']=$params['jwc_evaluate_id']; //评教id
        $post_params['bpr']=$params['jwc_teacher_id']; //教师id
        $post_params['pgnr']=$params['course_id']; //课程id

        //固定参数
        $post_params['oper']='wjShow'; //固定参数
        $post_params['pageSize']='20'; //固定参数
        $post_params['page']='1'; //固定参数
        $post_params['currentPage']='1'; //固定参数
        $post_params['pageNo']=''; //固定参数

        //中文需要先转换为GBK
        $post_params['wjmc']=$this->utf82gbk($params['evaluate_type']); //评教类型
        $post_params['bprm']=$this->utf82gbk($params['teacher_name']); //教师名
        $post_params['pgnrm']=$this->utf82gbk($params['course_name']); //课程名

        $post_params[$params['verify_name']]=$params['verify_value']; //应该是验证参数
        return $post_params;


    }

    protected function utf82gbk($str){
        $str=iconv('UTF-8','GBK//IGNORE',$str);
        return $str;
    }

}