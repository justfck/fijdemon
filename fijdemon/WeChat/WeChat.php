<?php
/*
 * 微信接口
 */
namespace fijdemon\WeChat;

use fijdemon\WeChat\behavior\Chart;
use fijdemon\WeChat\behavior\response\ResEntity;
use fijdemon\WeChat\behavior\response\Response;
/**
 * 微信插件接口
 +--------------------------------------------------
 * @author fijdemon
 * @creatTime 2015年01月31日(星期六)
 +--------------------------------------------------
 */
class WeChat{
    /**
     * 微信接口具体操作对象
     +--------------------------------------------------
     * @var class
     +--------------------------------------------------
     */
    private $wechart = null;
    
    /**
     * 初始化的时候加载需要的文件
     +--------------------------------------------------
     */
    public function __construct(){
        $path = dirname(__FILE__);
        $spr = DIRECTORY_SEPARATOR;
        //TODO inport important file
        require_once "{$path}{$spr}data{$spr}config.php";
        require_once "{$path}{$spr}data{$spr}MsgType.php";
        require_once "{$path}{$spr}behavior{$spr}fun{$spr}common.php";
        require_once "{$path}{$spr}behavior{$spr}Chart.php";
        require_once "{$path}{$spr}behavior{$spr}request{$spr}Request.php";
        require_once "{$path}{$spr}behavior{$spr}response{$spr}Response.php";
        require_once "{$path}{$spr}behavior{$spr}response{$spr}ResEntity.php";
        
        // 生成对象
        $this->wechart = new Chart();
    }
    
    
    /**
     * 初始化微信接口（预处理）
     +--------------------------------------------------
     */
    public function init(){
        $this->wechart->init();
    }
    
    
    /**
     * 得到微信消息类型
     +--------------------------------------------------
     * @return string
     +--------------------------------------------------
     */
    public function getType(){
        return $this->wechart->MsgType;
    }
    
    
    /**
     * 获取微信请求头数据
     +--------------------------------------------------
     * @param string $name
     +--------------------------------------------------
     */
    public function __get($name){
        return "{$this->wechart->$name}";
    }
    
    
    /**
     * 生成并获取 text 类型的xml数据
     +--------------------------------------------------
     * @param string $content
     * @return string
     +--------------------------------------------------
     */
    static public function text($content){
        $response = Response::init()->entity(ResEntity::text)->text($content)->getData();
        $res = $response->getTem();
        $xml = $res->xml->asXML();
        return $xml;
    }
    
    
    /**
     * 生成并获取 news 类型的xml数据
     +--------------------------------------------------
     * @param string $Title
     * @param string $Description
     * @param string $PicUrl
     * @param string $Ur
     * @return string
     +--------------------------------------------------
     */
    static public function news($Title, $Description, $PicUrl, $Url){
        $response = Response::init()->entity(ResEntity::news)->news($Title, $Description, $PicUrl, $Url)->getData();
        $res = $response->getTem();
        $xml = $res->xml->asXML();
        return $xml;
    }
    
    
    /**
     * 展示数据
     +--------------------------------------------------
     * @access public
     +--------------------------------------------------
     */
    static public function response(){
        Response::init()->show();
    }
}

?>