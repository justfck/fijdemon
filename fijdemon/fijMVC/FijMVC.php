<?php
/*  相关更新及其定义：
 * 
 * 2015/01/15
 * 1、不做自动文件结构生成，格式按照项目中的例子为主（省事儿）
 * 2、入口自动完成，不用调用init等方法
 * 3、规则类似thinkphp（毕竟我接触的框架不多！为什么不多！因为我懒！对我不能懒！）
 * 4、大题【流程】：安！全！性！检查！→__autoload  throw_exception→记载没用的自加载对象→对url的处理
 */
namespace fijdemon\fijMVC;

use fijdemon\fijMVC\behavior\common\AutoInport;
use fijdemon\fijMVC\behavior\controller\ParseUrl;
/**
 +--------------------------------------------------
 * 自制mvc入口
 * @author fijdemon
 * @startTime 2015年01月15日(星期四)
 +--------------------------------------------------
 */
class FijMVC {
    /**
     +--------------------------------------------------
     * 自动构建方法
     * 应实现自动寻找【group】【conctroller】【action】并调用相关页面及其函数
     +--------------------------------------------------
     */
    public function __construct(){
        $dir = DIRECTORY_SEPARATOR;
        // 检查安全性
        $this->check();
        
        // 加载必须的特殊函数（然后就能抛异常，自动加载了）
        require_once FIJMVC_PATH.'/behavior/common/SysFunReload.php';
        
        // 加载函数
        $autoInport = new AutoInport();
//         dump($_SERVER,1);
        // 执行url处理
        $urlinfo = new ParseUrl();
        
        // 调取action
        $pathinfo = $urlinfo->getPathinfo();//dump($pathinfo);
        AutoInport::inport(APP_PATH.$dir.$pathinfo[0].$dir.$pathinfo[1]."Controller.php");
        A("{$pathinfo[0]}/{$pathinfo[1]}")->$pathinfo[2]();
    }
    
    
    /**
     +--------------------------------------------------
     * 安全性检查
     +--------------------------------------------------
     */
    private function check(){
       // 检查常量
       $this->checkDefined();
    }
    
    
    /**
     +--------------------------------------------------
     * 检查必须常量定义
     +--------------------------------------------------
     */
    private function checkDefined(){
        // 顺便自定义一个
        define("__ROOT__", ".");
        define("__PACKAGE__", __ROOT__.DIRECTORY_SEPARATOR."package");
        // mvc根目录（这里还不能用 throw_excepton）
        if(!defined("FIJMVC_PATH"))exit( "未定义常量 ‘FIJMVC_PATH’");
        // app根目录
        if(!defined("APP_PATH"))exit( "未定义常量 ‘APP_PATH");
    }
}
