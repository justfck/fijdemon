<?php
/*
 * 本来是用来自动加载方法的，结果有了自动加载了，那么可能就没啥用了吧
 * 
 * 2015/01/15
 * nothing
 */
namespace fijdemon\fijMVC\behavior\common;

/**
 +--------------------------------------------------
 * 自动加载
 +--------------------------------------------------
 * @author fijdemon
 +--------------------------------------------------
 */
class AutoInport {
    public function __construct(){
        $this->autoFile();
        $this->autoFloder();
    }
    
    /**
     +--------------------------------------------------
     * 加载必须的文件
     +--------------------------------------------------
     */
    public function autoFile(){
        // 自定义方法
        require_once FIJMVC_PATH.'/behavior/common/Function.php';
        // 加载默认配置文件
        C(include FIJMVC_PATH."/data/config/config.php");
    }
    
    
    /**
     +--------------------------------------------------
     * 加载文件夹目录
     +--------------------------------------------------
     */
    public function autoFloder(){
        // self::config(APP_PATH."/common/config");
    }
    
    
    /**
     +--------------------------------------------------
     * 自动加载config
     +--------------------------------------------------
     * @param string $dir
     * @return boolean
     +--------------------------------------------------
     */
    static function config($dir){
        if(is_dir($dir)){
            $filesname = scandir($dir);
            for ($i = 2; $i < count($filesname); $i++) {
                $_file = "{$dir}/{$filesname[$i]}";
                if(is_file($_file))
                    C(include $_file);
            }
            return true;
        }else return false;
    }
    
    
    /**
     * 自动加载文件
     +--------------------------------------------------
     * @param string $file
     +--------------------------------------------------
     */
    static function inport( $file ){
        $dir = DIRECTORY_SEPARATOR;
        static $_file = array();
        if(isset($_file[$file])){
            return true;
        }elseif(is_file($file)){
            require_once $file;
            return true;
        }elseif(is_file(__PACKAGE__.$dir.$file)){
            return false;
        }
    }
}

?>