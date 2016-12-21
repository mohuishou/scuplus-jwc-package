<?php
namespace Mohuishou\Lib;
use Curl\Curl;
use QL\QueryList;
abstract class JwcBase{

    protected $_uid;

    protected $_password;

    /**
     * @var Curl
     */
    protected $_curl;

    /**
     * 模拟登录的cookie
     * @var string
     */
    protected $_login_cookie;

    /**
     * @var string 带抓取的链接
     */
    protected $_url;

    /**
     * 抓取信息类
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    abstract protected function spider();

    /**
     * 主要方法，所有返回结果都在这个类
     * @return mixed
     */
    public function index()
    {
        return $this->login()->spider();
    }


    /**
     * JwcBase constructor.
     * @author mohuishou<1@lailin.xyz>
     * @param $uid int 学号
     * @param $password string 密码
     * @param Curl $curl php-class-curl类
     */
    public function __construct(Curl $curl,$uid,$password)
    {
        $this->_uid=$uid;
        $this->_password=$password;
        $this->_curl=$curl;

    }


    /**
     * @author mohuishou<1@lailin.xyz>
     * @return $this
     * @throws \Exception
     */
    protected function login()
    {
        //判断是否已经登录
        if(!empty($this->_login_cookie))
            return $this;

        //设置header伪造来源以及ip
        $ip=rand(1,233).'.'.rand(1,233).'.'.rand(1,233).'.'.rand(1,233);
        $this->_curl->setHeader("X-Forwarded-For",$ip);
        $this->_curl->setHeader("Referer",'http://202.115.47.141/loginAction.do');

        //登录教务处
        $param=[
            "zjh"=>$this->_uid,
            "mm"=>$this->_password
        ];
        $this->_curl->setOpt(CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        $this->_curl->post('http://202.115.47.141/loginAction.do',$param);
        if ( $this->_curl->error) {
            throw new \Exception('Error: ' .  $this->_curl->errorCode . ': ' .  $this->_curl->errorMessage,5001);
        }

        //判断是否登录成功
        $page=$this->_curl->response;
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        $rule=[
            'err'=>['.errorTop','text']
        ];
        $err=QueryList::Query($page,$rule)->data;
        //登录失败，报错
        if(!empty($err))
            throw new \Exception('Error:'.$err[0]['err'],4011);

        //登录成功之后设置cookie
        $this->_login_cookie=$this->_curl->getResponseCookie("JSESSIONID");
        $this->_curl->setCookie('JSESSIONID',$this->_login_cookie);
        return $this;
    }

    /**
     * 通过get方法获得页面
     * @author mohuishou<1@lailin.xyz>
     * @param $url
     * @return string html页面
     * @throws \Exception
     */
    protected function get($url){
        //判断是否已经登录
        if(empty($this->_login_cookie))
            throw new \Exception('Error: 尚未登录');

        $this->_curl->setOpt(CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        $this->_curl->get($url);
        if ( $this->_curl->error) {
            throw new \Exception('Error: ' .  $this->_curl->errorCode . ': ' .  $this->_curl->errorMessage,5001);
        }
        $page=$this->_curl->response;
        //转码
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        return $page;
    }


}