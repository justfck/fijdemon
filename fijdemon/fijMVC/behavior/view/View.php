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
     * @param string $prefix 模板缓存前缀
     * @return void
     +--------------------------------------------------
     */
    public function display($templateFile='', $charset='', $contentType='' ,$prefix=''){
        // 解析模板内容
        $content = $this->fetch($templateFile, $prefix);
        
        // 保存入缓存文件
        $temFile = $this->Runtime($templateFile,$content);
        
        // 输出模板内容
        $this->render($temFile, $charset, $contentType);
    }
    
    
    /**
     * 解析和获取模板内容 用于输出
     +--------------------------------------------------
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $prefix 模板缓存前缀
     * @return string
     +--------------------------------------------------
     */
    public function fetch($templateFile='',$prefix='') {
        $templateFile   =   $this->parseTemplate($templateFile);
        // 模板文件不存在直接返回
        if(!is_file($templateFile)) throw_exception("未找到模板：".$templateFile,404);
//         // 页面缓存
//         ob_start();
//         ob_implicit_flush(0);
        // 载入PHP模板
       $temFile = $this->makeRuntimeTpl($templateFile);
        if(file_exists($temFile) && filemtime($temFile)> filemtime($templateFile)){//如果缓存文件存在则 并且文件没有修改了用缓存
            // 用缓存
            $content = file_get_contents($temFile);
        }else{
            // 加载解析
            $content = file_get_contents($templateFile);
            $content = $this->con_replace($content);
        }
        // 获取并清空缓存
//         $content = ob_get_clean();
        // 输出模板文件
        return $content;
    }
    
    /**
     * 添加到缓存文件中去
     +--------------------------------------------------
     * @param 源文件路径 $templateFile
     * @param 源文件已经被编译好的内容 $content
     * @return string 缓存文件路径
     +--------------------------------------------------
     */
    private function Runtime($templateFile,$content){
        // 写入runtime
        $temFile = $this->makeRuntimeTpl($templateFile);
        file_put_contents($temFile,$content);
        
        return $temFile;
    }
    
    
    /**
     +--------------------------------------------------
     * 输出内容文本可以包括Html
     +--------------------------------------------------
     * @access private
     * @param string $temFile 要输出的文件
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @return mixed
     +--------------------------------------------------
     */
    private function render($temFile,$charset='',$contentType=''){
        // 模板阵列变量分解成为独立变量
        extract($this->tVar, EXTR_OVERWRITE);
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
        header('X-Powered-By:ThinkPHP');
        // 输出模板文件
       include $temFile;
    }
    
    /**
     * 解析变量
     * @param string $content
     * @return string
     */
    public function con_replace($content){
        $pattern=array(
            '/{\s*\$([a-zA-Z_][a-zA-Z_0-9]*)\s*}/i'
        );
        $replacement=array(
            '<?php echo $${1} ?>'
        );
        return preg_replace($pattern,$replacement,$content);
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
    
    /**
     * 生成缓存文件文件名
     +----------------------------------------------------
     * @param string $template
     * @return string
     +----------------------------------------------------
     */
    private function makeRuntimeTpl($template){
        return  __ROOT__.DIRECTORY_SEPARATOR."Runtime".DIRECTORY_SEPARATOR."tpl".DIRECTORY_SEPARATOR.md5($template);
    }
}

?>