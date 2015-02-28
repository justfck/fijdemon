<?php
/*
 * 用消息队列处理问题
 */
namespace fijdemon\robot\common;

/**
 * 消息队列类
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月08日(星期日) 我的天！都已经00:44了！看来我要明天写了！今儿把数据库搞定了，ok收工！
 +--------------------------------------------------
 */
class TastQueue
{
    /**
     * 讲用户说的东西添加到数据库
     +--------------------------------------------------
     * @param 用户id $user
     * @param 用户说的话 $sth
     * @param 分词信息 $seg
     +--------------------------------------------------
     */
    static function saveWordInfo($user, $sth, $seg){
        // 2015年2月8日01:00:00 创建一个新的页面，接收这个的队列信息今儿就到这里了！
        // 2015年2月8日09:27:49 好了！行了继续吧！
        $queue = new \SaeTaskQueue('saveWordInfo');
        $data['user'] = $user;
        $data['sth'] = $sth;
        $data['seg'] = $seg;
        $data['plat'] = 'WeChat';
        $data = http_build_query($data);
        $re = $queue->addTask('/Wechat/Save/WordInfo/.html', $data);
        $ret = $queue->push();
        
        //任务添加失败时输出错误码和错误信息
        if ($ret === false)
            wechart_exception($queue->errmsg(),$queue->errno(),__FILE__);
    }
}

?>