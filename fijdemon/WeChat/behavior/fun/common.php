<?php
use fijdemon\WeChat\WeChat;
function wechart_exception($msg,$code=0,$filedir=''){
    //exit("{$filedir}:{$msg}");
    
    WeChat::text($msg);
    WeChat::response();
//     $response = Response::init()->entity(ResEntity::text)->text($msg)->getData();
//     $res = $response->getTem();
//     $xml = $res->xml->asXML();
//     header("Content-type:text/xml");
//     exit($xml);
}