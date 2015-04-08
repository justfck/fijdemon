<?php
/*
 * controller基类
 * 
 * 2015年01月25日(星期日)
 */
namespace fijdemon\fijMVC\behavior\controller;

use fijdemon\fijMVC\behavior\view\View;
/**
 * 控制器基类 抽象类
 +--------------------------------------------------
 * @author fijdemon
 +--------------------------------------------------
 */
abstract class Controller{
    /**
     * 视图实例对象
     +--------------------------------------------------
     * @var view
     * @access protected
     +--------------------------------------------------
     */
    protected $view = null;
    
    
    /**
     * 控制器初始化
     +--------------------------------------------------
     */
    public function __construct(){
        $this->view = new View();
    }
    
    /**
     +--------------------------------------------------
     * 模板变量赋值
     +--------------------------------------------------
     * @access public
     * @param mixed $name
     * @param mixed $value
     +--------------------------------------------------
     */
    public function assign($name,$value=''){
        $this->view->assign($name,$value);
    }
    
    /**
     +--------------------------------------------------
     * 加载模板
     +--------------------------------------------------
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     +--------------------------------------------------
     */
    protected function display($templateFile='', $charset='', $contentType='', $content='' ,$prefix=''){
        $this->view->display($templateFile, $charset, $contentType, $content ,$prefix);
    }
}

?>