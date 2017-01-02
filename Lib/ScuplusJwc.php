<?php
/**
 * 主文件，所有方法均通过该文件访问
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/7 0007
 * Time: 11:26
 */

namespace Mohuishou\Lib;

class ScuplusJwc{
    public static function create($class,$uid,$password){
        try{
            $curl=new CurlBase($uid,$password);
            $class='Mohuishou\Lib\\'.$class;
            $obj= new $class($curl,$uid,$password);
            return $obj;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}