<?php
/*
 * 回复xml的相关方法
 */
namespace fijdemon\WeChat\behavior\response;

use fijdemon\WeChat\behavior\response\ResEntity;
/**
 * 返回的各种方式（单态类）
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class Response{
    /**
     * 单态类内部记录点
     +--------------------------------------------------
     * @var self
     +--------------------------------------------------
     */
    static private $response = null;
    
    /**
     * 记录微信请求头的
     +--------------------------------------------------
     * @var &Request
     +--------------------------------------------------
     */
    private $request = null;
    
    /**
     * 记录当前要返回的实体
     +--------------------------------------------------
     * @var ResEntity
     +--------------------------------------------------
     */
    private $entity = null;
    
    /**
     * 构造方法
     +--------------------------------------------------
     * @param object $request 请求内容（对象）
     * @access private
     +--------------------------------------------------
     */
    private function __construct(){
        // 单态类
    }
    
    
    /**
     * 单态类的初始化方法
     +--------------------------------------------------
     * @return \fijdemon\WeChat\behavior\Response
     +--------------------------------------------------
     */
    static function init(){
        if(empty(self::$response))
            self::$response = new self();
        return self::$response;
    }
    
    
    /**
     * 生成内部使用实体的方法
     +--------------------------------------------------
     * @param string $type
     +--------------------------------------------------
     */
    public function entity($type){
        if(empty($this->entity))
            $this->entity = ResEntity::create($type);
        return $this;
    }
    
    
    /**
     * 调用方法返回缓存
     +--------------------------------------------------
     * @var unknown
     +--------------------------------------------------
     */
    private $_tem = null;
    public function getTem(){
        return $this->_tem;
    }
    
    /**
     * 让直接调用entity里的内容成为可能
     +--------------------------------------------------
     * @param string $method
     * @param array $param
     * @return \fijdemon\WeChat\behavior\response\Response
     +--------------------------------------------------
     */
    public function __call($method, $param=array()){
        if(method_exists($this, $method)){
            $this->_tem = call_user_func_array(array($this, $method),$param);
        }else{
            $this->_tem = call_user_func_array(array($this->entity,$method),$param);
        }
        return $this;
    }
    
    
    /**
     * 直接展示数据
     +--------------------------------------------------
     * @access public
     +--------------------------------------------------
     */
    public function show(){
        $data = $this->entity->getData();
        header("Content-type:text/xml");
        exit($data->asXML());
    }
}

?>