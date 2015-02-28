<?php
/**
 +--------------------------------------------------
 * 重写自动加载函数
 * @param string $class
 +--------------------------------------------------
 */
function __autoload($className) {
    // 自动加载路径列表
    $inportPath = array(
        __ROOT__.DIRECTORY_SEPARATOR."package".DIRECTORY_SEPARATOR,
        __ROOT__.DIRECTORY_SEPARATOR,
        FIJMVC_PATH.DIRECTORY_SEPARATOR,
        APP_PATH.DIRECTORY_SEPARATOR,
        APP_PATH.DIRECTORY_SEPARATOR."Common".DIRECTORY_SEPARATOR."fun".DIRECTORY_SEPARATOR,
    );
    
    $fileName = '';
    $namespace = '';

    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    // 遍历尝试目录
    foreach ($inportPath as $v){
        if (file_exists( $v.$fileName.$className.".php" )) {
            return require_once $v.$fileName.$className.".php";
        } elseif (file_exists( $v.$fileName.$className.".class.php" )) {
            return require_once $v.$fileName.$className.".class.php";
        }
    }
    // 没有返回找到
    throw_exception("未找到类 ".$v.$fileName.$className.".php");
}


/**
 +--------------------------------------------------
 * 自定义错误抛出（必须有exit啊）
 +--------------------------------------------------
 * @param string $msg
 * @param int $level
 * @param string $fileDir
 +--------------------------------------------------
 */
function throw_exception( $msg, $level=0, $fileDir=""){
    exit($fileDir.":".$msg);
}


/**
 +--------------------------------------------------
 * 获取和设置配置参数 支持批量定义
 +--------------------------------------------------
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 +--------------------------------------------------
 */
function C($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtolower($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name));
        return;
    }
    return null; // 避免非法参数
}


/**
 +--------------------------------------------------
 * 暂时的日志方法
 +--------------------------------------------------
 * @param string $msg
 +--------------------------------------------------
 */
// function log($msg){
//     static $log = array();
//     $msg[] = array(
//         'time'=>time(),
//         'msg'=>$msg,
//     );
// }