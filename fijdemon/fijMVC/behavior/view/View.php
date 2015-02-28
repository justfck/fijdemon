<?php
/*
 * 解析模板
 * 
 * 2015年01月25日(星期日)
 */
namespace fijdemon\fijMVC\behavior\view;

class View{
    /**
     +--------------------------------------------------
     * 模板输出变量
     +--------------------------------------------------
     * @var tVar
     * @access protected
     +--------------------------------------------------
     */ 
    protected $tVar     =   array();

    /**
     +--------------------------------------------------
     * 模板主题
     +--------------------------------------------------
     * @var theme
     * @access protected
     +--------------------------------------------------
     */ 
    protected $theme    =   '';

    /**
     +--------------------------------------------------
     * 模板变量赋值
     +--------------------------------------------------
     * @access public
     * @param mixed $name
     * @param mixed $value
     +--------------------------------------------------
     */
    public function assign($name,$value=''){
        if(is_array($name)) {
            $this->tVar   =  array_merge($this->tVar,$name);
        }else {
            $this->tVar[$name] = $value;
        }
    }

    /**
     +--------------------------------------------------
     * 取得模板变量的值
     +--------------------------------------------------
     * @access public
     * @param string $name
     * @return mixed
     +--------------------------------------------------
     */
    public function get($name=''){
        if('' === $name) {
            return $this->tVar;
        }
        return isset($this->tVar[$name])?$this->tVar[$name]:false;
    }
    
    /**
     +--------------------------------------------------
     * 加载模板
     +--------------------------------------------------
     * @access public
     * @param string $templateFile 指定要调用的模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     +--------------------------------------------------
     */
    public function display($templateFile='', $charset='', $contentType='', $content='' ,$prefix=''){
        // 解析模板内容
        $content = $this->fetch($templateFile, $content, $prefix);
        // 输出模板内容
        $this->render($content, $charset, $contentType);
    }
    
    
    /**
     * 解析和获取模板内容 用于输出
     +--------------------------------------------------
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀
     * @return string
     +--------------------------------------------------
     */
    public function fetch($templateFile='',$content='',$prefix='') {
        if(empty($content)) {
            $templateFile   =   $this->parseTemplate($templateFile);
            // 模板文件不存在直接返回
            if(!is_file($templateFile)) throw_exception("未找到模板：".$templateFile,404);
        }
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
//         if('php' == strtolower(C('TMPL_ENGINE_TYPE'))) { // 使用PHP原生模板
            // 模板阵列变量分解成为独立变量
            extract($this->tVar, EXTR_OVERWRITE);
            // 直接载入PHP模板
            empty($content)?include $templateFile:eval('?>'.$content);
//         }else{
//         }
        // 获取并清空缓存
        $content = ob_get_clean();
        // 输出模板文件
        return $content;
    }

    /**
     +--------------------------------------------------
     * 输出内容文本可以包括Html
     +--------------------------------------------------
     * @access private
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @return mixed
     +--------------------------------------------------
     */
    private function render($content,$charset='',$contentType=''){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
        header('X-Powered-By:ThinkPHP');
        // 输出模板文件
        echo $content;
    }
    
    /**
     +--------------------------------------------------
     * 自动定位模板文件
     +--------------------------------------------------
     * @access protected
     * @param string $template 模板文件规则
     * @return string
     +--------------------------------------------------
     */
    public function parseTemplate($template='') {
        if(is_file($template)) {
            return $template;
        }
        $depr   =  DIRECTORY_SEPARATOR;
        $template = str_replace(':', $depr, $template);
    
        // 获取当前模块
        $module   =  GROUP_NAME;
        if(strpos($template,'@')){ // 跨模块调用模版文件
            list($module,$template)  =   explode('@',$template);
        }
        
        // 获取当前主题的模版路径
        if(!defined('VIEW_PATH')){
            define('VIEW_PATH',   APP_PATH.$depr.$module.$depr.C('DEFAULT_V_LAYER'));
        }
    
        // 分析模板文件规则
        if('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = CONTROLLER_NAME . $depr . ACTION_NAME;
        }elseif(false === strpos($template, $depr)){
            $template = CONTROLLER_NAME . $depr . $template;
        }
        $file   =   VIEW_PATH.$depr.$template.'.'.C('TMPL_TEMPLATE_SUFFIX');
//         if(C('TMPL_LOAD_DEFAULTTHEME') && THEME_NAME != C('DEFAULT_THEME') && !is_file($file)){
//             // 找不到当前主题模板的时候定位默认主题中的模板
//             $file   =   dirname(THEME_PATH).'/'.C('DEFAULT_THEME').'/'.$template.C('TMPL_TEMPLATE_SUFFIX');
//         }
        return $file;
    }
}

?>