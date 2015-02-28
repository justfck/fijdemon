<?php
namespace fijdemon\WeChat\data;

class MsgType{
    /**
     * 文本消息
     +--------------------------------------------------
     */
    const text = 'text';
    
    /**
     * 图片消息
     +--------------------------------------------------
     */
    const image = 'image';
    
    /**
     * 语音消息
     +--------------------------------------------------
     */
    const voice ='voice';

    /**
     * 视频消息
     +--------------------------------------------------
     */
    const video = 'video';

    /**
     * 地理位置消息
     +--------------------------------------------------
     */
    const location = 'location';
    
    /**
     * 链接消息
     +--------------------------------------------------
     */
    const link = 'link';
    
    /**
     * 事件
     +--------------------------------------------------
     */
    const event = 'event';
    
    /**
     * 事件 订阅 （记得处理扫描二维码的用户 带场景的）
     +--------------------------------------------------
     */
    const event_subscribe = 'subscribe';

    /**
     * 事件 取消订阅
     +--------------------------------------------------
     */
    const event_unsubscribe = 'unsubscribe';
    
    /**
     * 事件 用户已关注时的事件推送
     +--------------------------------------------------
     */
    const event_SCAN = 'SCAN';
    
    /**
     * 事件 推送地址
     +--------------------------------------------------
     */
    const event_LOCATION = 'LOCATION';

    /**
     * 事件 菜单点击
     +--------------------------------------------------
     */
    const event_CLICK = 'CLICK';

    /**
     * 事件 点击跳转
     +--------------------------------------------------
     */
    const event_VIEW = 'VIEW';

    
    
    
    /**
     * 判断是否有此类别
     +--------------------------------------------------
     * @param string $type
     * @return boolean|string;
     +--------------------------------------------------
     */
    static function in_MsgType($type){
        if(isset(self::$type))return false;
        else return self::$type;
    }
}

?>