<?php
/*
 * 解析url的对象
 * 
 * 2015年01月16日(星期五)
 */
namespace fijdemon\fijMVC\behavior\controller;

use fijdemon\fijMVC\behavior\controller\Urlmode;

/**
 +--------------------------------------------------
 * 处理url模式
 * @author fijdemon
 * @startTime 2015年01月16日(星期五)
 +--------------------------------------------------
 */
class ParseUrl{
    // url 模式
    private $urlmode = UrlMode::PATHINFO;
    // 保存path信息的
    private $urlinfo;
    
    public function __construct(){
        $this->parse();
        //echo Urlmode::PATHINFO;
    }
    
    
    /**
     +--------------------------------------------------
     * 设置url模式
     +--------------------------------------------------
     */
    public function setUrlmode( $mode ){
        // 安全性验证
        if( Urlmode::is_mode($mode) ){
            $this->urlmode = $mode;
        }
    }
    
    
    /**
     +--------------------------------------------------
     * 解析url（其实是路由）
     +--------------------------------------------------
     */
    private function parse(){
        switch ($this->urlmode){
            case Urlmode::PATHINFO:
                return $this->parse_pathinfo();
            default:
        }
    }
    
    
    /**
     +--------------------------------------------------
     * 解析pathinfo模式
     +--------------------------------------------------
     */
    private function parse_pathinfo(){
        $urlinfo = &$this->urlinfo;
        
        // 默认pathinfo赋值
        $_SERVER['REDIRECT_URL'] = empty($_SERVER['REDIRECT_URL']) ? "/" : $_SERVER['REDIRECT_URL'];
        
        // 分解pathinfo
        $urlinfo['pathinfo'] = substr($_SERVER['REDIRECT_URL'],1);   // url【原始参数string】
        $urlinfo['path'] = explode('/', $urlinfo['pathinfo']);   // url【原始参数array】
        
        // 如果最后没有后缀，则创建默认文件
        if( strpos($urlinfo['path'][count($urlinfo['path'])-1],".")===false){
            $urlinfo['path'][] = ".".C('FILE_SUFFIX');
        }
        // url长度安全性检查
        if(count($urlinfo['path'])>4){
            throw_exception("文件未找到！",404);
        }
        
        // 拆解参数
        $paramStr = array_pop($urlinfo['path']);
        $paramArr = explode(".", $paramStr);
        $param = $paramArr[0];
        $urlinfo['param'] = explode("-", $param);   // 保存【参数】
        $urlinfo['suffix'] = $paramArr[1];  // 保存【后缀】
        
        // 给path赋默认值
        $urlinfo['path'][0] = empty($urlinfo['path'][0]) ? C('GROUP_DEFAULT') : $urlinfo['path'][0];
        $urlinfo['path'][1] = empty($urlinfo['path'][1]) ? C('CONCTROLLOR_DEFAULT') : $urlinfo['path'][1];
        $urlinfo['path'][2] = empty($urlinfo['path'][2]) ? C('ACTION_DEFAULT') : $urlinfo['path'][2];
        // 定义常量
        define("GROUP_NAME",          $urlinfo['path'][0]);
        define("CONTROLLER_NAME", $urlinfo['path'][1]);
        define("ACTION_NAME",        $urlinfo['path'][2]);
    }
    
    
    /**
     +--------------------------------------------------
     * 得到pathinfo中的相关action的信息
     +--------------------------------------------------
     * return array(groupName,controller,action);
     +--------------------------------------------------
     */
    public function getPathinfo(){
        return $this->urlinfo['path'];
    }
}



?>