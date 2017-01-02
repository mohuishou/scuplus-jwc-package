<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/1/2
 * Time: 13:54
 */

namespace Mohuishou\Lib;

use Curl\Curl;
use QL\QueryList;

class CurlBase extends Curl
{

    public $_uid;
    public $_password;
    public $_login_cookie;

    public function __destruct()
    {
        $this->logout();
    }

    public function __construct($uid,$password,$base_url='')
    {
        $this->_uid=$uid;
        $this->_password=$password;
        parent::__construct($base_url);
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @return $this
     * @throws \Exception
     */
    public function login_jwc()
    {
        //判断是否已经登录
        if(!empty($this->_login_cookie))
            return $this;

        //设置header伪造来源以及ip
        $ip=rand(1,233).'.'.rand(1,233).'.'.rand(1,233).'.'.rand(1,233);
        $this->setHeader("X-Forwarded-For",$ip);
        $this->setHeader("Referer",'http://202.115.47.141/loginAction.do');

        //登录教务处
        $param=[
            "zjh"=>$this->_uid,
            "mm"=>$this->_password
        ];
        $this->setOpt(CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        $this->post('http://202.115.47.141/loginAction.do',$param);
        if ( $this->error) {
            throw new \Exception('Error: ' .  $this->errorCode . ': ' .  $this->errorMessage,5001);
        }

        //判断是否登录成功
        $page=$this->response;
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        $rule=[
            'err'=>['.errorTop','text']
        ];
        $err=QueryList::Query($page,$rule)->data;
        //登录失败，报错
        if(!empty($err)){
            if(isset($err[0]['err'])&&!empty($err[0]['err'])){
                $msg=$err[0]['err'];
            }else{
                $msg="未知错误";
            }
            throw new \Exception('Error:'.$msg,4011);
        }

        //登录成功之后设置cookie
        $this->_login_cookie=$this->getResponseCookie("JSESSIONID");
        $this->setCookie('JSESSIONID',$this->_login_cookie);
        return $this;
    }

    /**
     * 通过get方法获得页面
     * @author mohuishou<1@lailin.xyz>
     * @param $url
     * @return string html页面
     * @throws \Exception
     */
    public function get_jwc($url){
        //判断是否已经登录
        if(empty($this->_login_cookie))
            throw new \Exception('Error: 尚未登录');

        $this->setOpt(CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        $this->get($url);
        if ( $this->error) {
            throw new \Exception('Error: ' .  $this->errorCode . ': ' .  $this->errorMessage,5001);
        }
        $page=$this->response;
        //转码
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        return $page;
    }

    /**
     * 退出登录
     * @author mohuishou<1@lailin.xyz>
     */
    public function logout(){
        //判断是否已经登录
        if(empty($this->_login_cookie))
            return;
        $url="http://202.115.47.141/logout.do?loginType=platformLogin";
        $page=$this->get($url);
    }
}