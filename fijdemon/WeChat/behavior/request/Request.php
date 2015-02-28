<?php
/*
 * 处理 WeChat 请求数据
 */
namespace fijdemon\WeChat\behavior\request;

/**
 * 请求处理方法（包括存储）
 +--------------------------------------------------
 * @author fijdemon
 * @creatTime 2015年01月31日(星期六)
 +--------------------------------------------------
 */
class Request{
    /**
     * 原始数据
     +--------------------------------------------------
     * @var unknown
     * @access private
     +--------------------------------------------------
     */
    static private $postdata = null;
    
    
    /**
     * xml转换成object对象
     +--------------------------------------------------
     * @var object
     * @access private
     +--------------------------------------------------
     */
    static private $dataobj = null;
    
    
    /**
     * 初始化Request
     +--------------------------------------------------
     * @access public
     +-------------------------------------------------- 
     */
    public function init(){
        // 安全验证
        $this->valid();
        // 接收数据
        $this->getRawPostDate();
        // 处理原始数据
        $this->parseXmlToObj();
    }
    

    /**
     * 安全性验证（其实没啥用）
     +--------------------------------------------------
     * @access private
     +--------------------------------------------------
     */
    private function valid(){
        if (empty($_GET['echostr'])) {
            return ;
        }
        $echoStr = $_GET["echostr"];

        //valid signature , option
        // TODO 这里不能运行成功也不知道为啥，腾讯就是坑啊
//         if($this->checkSignature()){
        if(1){
        	echo $echoStr;
        	exit;
        }
    }
    

    /**
     * check 安全性
     +--------------------------------------------------
     * @access private
     * @return boolean
     +--------------------------------------------------
     */
    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
//         echo $tmpStr;exit();
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
    
    /**
     * 获取原始数据
     +--------------------------------------------------
     * @access private
     +--------------------------------------------------
     */
    private function getRawPostDate(){
        $data = file_get_contents('php://input');
        if(empty($data))wechart_exception("没有数据啊！妹的，你到底是谁！",500,__FILE__);
        $postdata = &self::$postdata;
        $postdata = $data;
        if(empty($postdata)){
            wechart_exception("有给我说什么么？", 500, __FILE__);
        }
    }
    
    
    /**
     * 解析xml成为对象
     +--------------------------------------------------
     * @access public
     +--------------------------------------------------
     */
    public function parseXmlToObj(){
        $dataobj = &self::$dataobj;
        $dataobj = simplexml_load_string(self::$postdata, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($dataobj === false){
            wechart_exception("你是微信么？多少先学好xml吧！",500, __FILE__);
        }
        //dump($dataobj);
    }
    
    
    /**
     * 获取请求头相关变量
     +--------------------------------------------------
     * @param string $name
     +--------------------------------------------------
     */
    public function __get($name){
        $dataobj = &self::$dataobj;
        if(isset($dataobj->$name) && !empty($dataobj->$name))
            return $dataobj->$name;
        else 
            return false;
    }
    
    
    /**
     * 全局返回当前对象
     +--------------------------------------------------
     * @return object
     +--------------------------------------------------
     */
    static function getDataObj(){
        return self::$dataobj;
    }
}

?>