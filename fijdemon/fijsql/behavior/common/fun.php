<?php
/**
 * 错误方法
 +--------------------------------------------------
 * @param unknown $msg
 * @param number $code
 * @param string $filedir
 +--------------------------------------------------
 */
function fijsql_exception($msg,$code=0,$filedir=''){
    exit($msg);
}