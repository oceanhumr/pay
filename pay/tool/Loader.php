<?php
// +----------------------------------------------------------------------
// | LIKE [ THERE IS NO IF ]
// +----------------------------------------------------------------------
// | Author: Mr.hu <huhaiyang7788@163.com>
// +----------------------------------------------------------------------
// | DESC: 自动加载类文件
// +----------------------------------------------------------------------

namespace pay\tool;


class Loader
{
    protected static $instance = [];
    // 类名映射
    protected static $map = [];

    // 自动加载
    public static function autoload($class)
    {

        if ($file = self::findFile($class)) {

            // Win环境严格区分大小写
            if (PHP_OS==='WINNT' && pathinfo($file, PATHINFO_FILENAME) != pathinfo(realpath($file), PATHINFO_FILENAME)) {
                return false;
            }

            __include_file($file);
            return true;
        }
    }

    /**
     * 查找文件
     * @param $class
     * @return bool
     */
    private static function findFile($class)
    {
        // 查找 PSR-4
        $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
        return $logicalPathPsr4;
    }

    // 注册自动加载机制
    public static function register($autoload = '')
    {
        // 注册系统自动加载
        spl_autoload_register($autoload ?: 'pay\\tool\\Loader::autoload', true, true);
    }

}

/**
 * 作用范围隔离
 *
 * @param $file
 * @return mixed
 */
function __include_file($file)
{
    return include $file;
}

function __require_file($file)
{
    return require $file;
}
