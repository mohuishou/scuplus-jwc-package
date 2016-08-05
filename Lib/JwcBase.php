<?php
namespace Mohuishou\Lib;
use Curl\Curl;
use QL\QueryList;

abstract class JwcBase{
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
     * @param $uid int 学号
     * @param $password string 密码
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    public function index($uid,$password)
    {
        return $this->login($uid,$password)->spider();
    }


    /**
     * JwcBase constructor.
     * @param Curl $curl php-class-curl类
     */
    public function __construct(Curl $curl)
    {
        $this->_curl=$curl;
    }


    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $uid
     * @param $password
     * @return $this
     * @throws \Exception
     */
    protected function login($uid,$password)
    {
        //判断是否已经登录
        if(!empty($this->_login_cookie))
            return $this;

        //设置header伪造来源以及ip
        $ip=rand(1,233).'.'.rand(1,233).'.'.rand(1,233).'.'.rand(1,233);
        $this->_curl->setHeader("X-Forwarded-For",$ip);
        $this->_curl->setHeader("Referer",'http://202.115.47.141/login.jsp');

        $this->_curl->get('http://202.115.47.141/loginAction.do?zjh='.$uid.'&mm='.$password);
        if ( $this->_curl->error) {
            throw new \Exception('Error: ' .  $this->_curl->errorCode . ': ' .  $this->_curl->errorMessage);
        }
        //判断是否登录成功
        $page=$this->_curl->response;
        $page=iconv('GBK','UTF-8//IGNORE',$page);
        $rule=[
            'err'=>['.errorTop','text']
        ];
        $err=QueryList::Query($page,$rule)->data;
        if(!empty($err))
            throw new \Exception('Error:'.$err[0]['err']);

        //登录成功之后设置cookie
        $this->_login_cookie=$this->_curl->getResponseCookie("JSESSIONID");
        $this->_curl->setCookie('JSESSIONID',$this->_login_cookie);
        return $this;

    }
}