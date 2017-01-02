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
    public function __construct(CurlBase $curl,$uid,$password)
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
    protected function login(){
        $this->_curl->login_jwc();
        return $this;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $url
     * @return string
     */
    protected function get($url){
        return $this->_curl->get_jwc($url);
    }

    /**
     * 退出登录
     * @author mohuishou<1@lailin.xyz>
     * @throws \Exception
     */
    public function logout(){
        $this->_curl->logout();
    }



}