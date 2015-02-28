<?php
/*
 * 接口总处理的详细执行
 */
namespace fijdemon\WeChat\behavior;

use fijdemon\WeChat\behavior\request\Request;
use fijdemon\WeChat\behavior\response\Response;
/**
 * 接口总处理的详细执行
 +--------------------------------------------------
 * @author fijdemon
 * @creatTime 2015年01月31日(星期六)
 +--------------------------------------------------
 */
class Chart{
    private $request = null;
    
    /**
     * 初始化
     +--------------------------------------------------
     */
    public function init(){
        // TODO 初始化
        $this->request = new Request();
        $this->request->init();
    }
    
    
    /**
     * 内部属性的请求
     +--------------------------------------------------
     * @param string $name
     +--------------------------------------------------
     */
    public function __get($name){
        // 如果是请求头里的直接返回
        if($this->request->$name !== false) return $this->request->$name;
    }
    
    
    public function getResponseObj(){
        return Response::init($this->request);
    }
    
}

?>