<?php
/*
 * 机器人的入口初始化及其调用文件
 */
namespace fijdemon\robot;

use fijdemon\robot\segment\FijSegment;
use fijdemon\robot\common\TastQueue;
/**
 * 机器人入口类
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月06日(星期五)
 +--------------------------------------------------
 */
class FijRobot{
    /**
     * 机器人初始化操作
     +--------------------------------------------------
     * @access public
     +--------------------------------------------------
     */
    public function __construct(){
        // 加载文件
        $this->importFile();
    }
    
    
    /**
     * 加载机器人相关文件
     +--------------------------------------------------
     * @access private
     +--------------------------------------------------
     */
    private function importFile(){
        $path = dirname(__FILE__);
        $spr = DIRECTORY_SEPARATOR;
        // include important files
        require_once "{$path}{$spr}segment{$spr}FijSegment.php";
        require_once "{$path}{$spr}common{$spr}TastQueue.php";
    }
    
    
    /**
     * 处理当前字符串
     +--------------------------------------------------
     * @param string $somebody 用户标识
     * @param string $thing 用户数说的话
     +--------------------------------------------------
     */
    public function deal($somebody, $thing){
        // 分词解析
        $segment = FijSegment::init();
        $word = $segment->string($thing);

        // 添加入任务队列
        TastQueue::saveWordInfo($somebody, $thing, $word);
        
        return $word;
    }
}

?>