<?php
// +----------------------------------------------------------------------
// | LIKE [ THERE IS NO IF ]
// +----------------------------------------------------------------------
// | Author: Mr.hu <huhaiyang7788@163.com>
// +----------------------------------------------------------------------
// | DESC:创建对应的驱动
// +----------------------------------------------------------------------
namespace pay\tool;

class Factory{
    private static $drivers=[];
    /**
     * 实例化对应的支付驱动
     * @param $driver_name
     * @ Mr.hu.
     */
    public static function getDriver($driver_name)
    {
        if(isset(self::$drivers[$driver_name])){
            return self::$drivers[$driver_name];
        }else{
            $class_full_name="pay\\driver\\".ucfirst($driver_name);
            $driverObj=new $class_full_name();
            self::$drivers[$driver_name]=$driverObj;
            return self::$drivers[$driver_name];
        }

    }

}
