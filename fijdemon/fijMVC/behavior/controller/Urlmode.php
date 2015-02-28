<?php
namespace fijdemon\fijMVC\behavior\controller;

/**
 +--------------------------------------------------
 * urlmode类型结构体，算是结构体吧
 * @author fijdemon
 +--------------------------------------------------
 */
class Urlmode{
    const PATHINFO = 1;// pathinfo
    
    static function is_mode( $mode ){
        if(!in_array( $mode, array(
            1,
        ))){
            return false;
        }else{
            return $mode;
        }
    }
}

?>