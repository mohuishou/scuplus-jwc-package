<?php
/**
 * 主文件，所有方法均通过该文件访问
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/7 0007
 * Time: 11:26
 */

namespace Mohuishou\Lib;
use Curl\Curl;

class ScuplusJwc{
    public static function create($class,$uid,$password){
        try{
            $curl=new Curl();
            $class='Mohuishou\Lib\\'.$class;
            return new $class($curl,$uid,$password);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}