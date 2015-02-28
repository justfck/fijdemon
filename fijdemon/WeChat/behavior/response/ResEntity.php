<?php
/*
 * 微信返回类型的对象
 */
namespace fijdemon\WeChat\behavior\response;

use fijdemon\WeChat\behavior\request\Request;
/**
 * 返回数据的对象（不可new的类）
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResEntity{
    const text = 'text'; // 文字
    const image = 'image';  // 图片
    const voice = 'voice';    // 声音
    const video = 'video';    // 视频
    const music = 'music';    // 音乐
    const news = 'news'; // 图文
    
    
    /**
     * 保存数据的
     +--------------------------------------------------
     * @var array
     +--------------------------------------------------
     */
    private $data = array();
    
    
    /**
     * 初始化，由create函数调用
     +--------------------------------------------------
     * @param string $type
     * @param string $ToUserName
     * @param string $FromUserName
     * @param int $CreateTime
     * @access private
     +--------------------------------------------------
     */
    private function __construct($type){
        $data = &$this->data;
        switch ($type){
            case self::text:
                $data = new ResText();
                break;
            case self::news:
                $data = new ResNews();
                break;
            case self::image:
                $data = new ResImage();
                break;
            case self::voice:
                $data = new ResVoice();
                break;
            case self::video:
                $data = new ResVideo();
                break;
            case self::music:
                $data = new ResMusic();
                break;
            default:
                wechart_exception("无此类型", 500, __FILE__);
        }
        $this->setInit();
    }
    
    
    /**
     * 创建对象
     +--------------------------------------------------
     * @param string $type
     * @param string $ToUserName
     * @param string $FromUserName
     * @param int $CreateTime
     * @return \fijdemon\WeChat\behavior\response\ResponseEntity
     +--------------------------------------------------
     */
    static function create($type){
        return new self($type);
    }
    
    
    /**
     * 初始化共有内容
     +--------------------------------------------------
     * @param string $ToUserName
     * @param string $FromUserName
     * @param int $CreateTime
     +--------------------------------------------------
     */
    public function setInit(){
        $request = Request::getDataObj();
        $FromUserName = empty($request) ? "" : $request->FromUserName;
        $ToUserName = empty($request) ? "" : $request->ToUserName;
        $CreateTime = time();
        
        CDATA($this->data->ToUserName, $FromUserName);
        CDATA($this->data->FromUserName, $ToUserName);
        CDATA($this->data->CreateTime, $CreateTime);
    }
    
    
    /**
     * 返回数据内容
     +--------------------------------------------------
     * @return multitype:
     * @access public
     +--------------------------------------------------
     */
    public function getData(){
        return $this->data;
    }
    
    /**
     * 生成text的方法
     +--------------------------------------------------
     * @param string $content
     +--------------------------------------------------
     */
    public function text($content){
        CDATA($this->data->Content, $content);
    }
    
    
    /**
     * 生成图文的方法
     +--------------------------------------------------
     * @param string $Title
     * @param string $Description
     * @param string $PicUrl
     * @param string $Url
     +--------------------------------------------------
     */
    public function news($Title, $Description, $PicUrl, $Url){
        $this->data->news($Title, $Description, $PicUrl, $Url);
    }
}


/*
 * ====================================================================================
 * 下面是定义Entity用的实体的类，基本上别的类都用不上，所以，不看也罢了
 * ====================================================================================
 */

function CDATA($obj, $cdata_text){
    $node = dom_import_simplexml($obj);
    $no   = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
}


/**
 * 所有返回类型的抽象类
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
abstract class Res{
    public $xml = null;
    public function __construct(){
        $data = <<<STRING
<xml>
<ToUserName/>
<FromUserName/>
<CreateTime/>
<MsgType/>
</xml>
STRING;
        $this->xml = new \SimpleXMLElement($data);
        //$this->CreateTime = time();
    }
    
    public function __get($name){
        return $this->xml->$name;
    }
    
    public function __set($name,$value){
        if(is_string($value)){
            CDATA($this->xml->$name, $value);
        }elseif(is_numeric($value)){
            $this->xml->$name = $value;
        }
    }
    
    public function asXML(){
        return $this->xml->asXML();
    }
}


/**
 * 文本
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResText extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::text);
        $this->xml->addChild("Content");
    }
}


/**
 * 图片
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResImage extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::image);
        $this->xml->addChild('Image');
        $this->xml->Image->addChild('MediaId');
    }
}

/**
 * 语音
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResVoice extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::voice);
        $this->xml->addChild('Voice');
        $this->xml->Voice->addChild('MediaId');
    }
}


/**
 * 视频
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResVideo extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::video);
        $this->xml->addChild('Voice');
        $this->xml->Voice->addChild('MediaId');
        $this->xml->Voice->addChild('Title');
        $this->xml->Voice->addChild('Description');
    }
}


/**
 * 音乐
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResMusic extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::music);
        $this->xml->addChild('Music');
        $this->xml->Music->addChild('Title');
        $this->xml->Music->addChild('Description');
        $this->xml->Music->addChild('MusicUrl');
        $this->xml->Music->addChild('HQMusicUrl');
        $this->xml->Music->addChild('ThumbMediaId');
    }
}


/**
 * 图文
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月01日(星期日)
 +--------------------------------------------------
 */
class ResNews extends Res{
    public function __construct(){
        parent::__construct();
        CDATA($this->xml->MsgType,ResEntity::news);
        $this->xml->addChild('ArticleCount',0);
        $this->xml->addChild('Articles');
        //$this->xml->Articles->addChild('item');
    }
    public function news($Title, $Description, $PicUrl, $Url){
        $cnt = (int)($this->xml->ArticleCount);
        $this->xml->Articles->addChild('item');
        $item = $cnt!=0 ? $this->xml->Articles->item[$cnt] : $this->xml->Articles->item;
        $item->addChild('Title');CDATA($item->Title, $Title);
        $item->addChild('Description');CDATA($item->Description, $Description);
        $item->addChild('PicUrl');CDATA($item->PicUrl, $PicUrl);
        $item->addChild('Url');CDATA($item->Url, $Url);
        $this->xml->ArticleCount++;
        return $this;
    }
}
?>