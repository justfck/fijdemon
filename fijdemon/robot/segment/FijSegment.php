<?php
/*
 * 处理分词的技术（暂包含分析语法的功能）
 */
namespace fijdemon\robot\segment;

/**
 * 分词处理（单态类）
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月06日(星期五)
 +--------------------------------------------------
 */
class FijSegment{
    static private $self = null;   // 缓存自己
    private $seg = null;    // 缓存sae分词技术类对象
    /**
     * 实现单态
     +--------------------------------------------------
     * @access private
     +--------------------------------------------------
     */
    private function __construct(){
        $this->seg = new \SaeSegment();
    }
    
    
    /**
     * 单态类的初始化操作
     +--------------------------------------------------
     * @return \fijdemon\robot\segment\FijSegment
     +--------------------------------------------------
     */
    static function init(){
        if(self::$self == null)
            return self::$self = new self();
        return self::$self;
    }
    
    
    /**
     * 解析句子
     +--------------------------------------------------
     * @param string $str
     * @access public
     +--------------------------------------------------
     */
    public function string($str){
        // 这里用sae的接口实现分词
		$ret = $this->seg->segment($str, 1);
		for($i = 0; $ret!==false && $i < count($ret); $i++){
		    $ret[$i]['word_tag_zh'] = $this->postTag($ret[$i]['word_tag']);
		}
		
		return $ret;
    }

    
    /**
     * 解析分词中的 word_tag含义
     +--------------------------------------------------
     * @param int $tag
     * @return Ambigous <boolean, multitype:string >
     +--------------------------------------------------
     */
    private function postTag($tag){
        $postTag = array(0=>array('不知道','POSTAG_ID_UNKNOW'),10=>array('形容词','POSTAG_ID_A'),20=>array('区别词','POSTAG_ID_B'),30=>array('连词','POSTAG_ID_C'),31=>array('体词连接','POSTAG_ID_C_N'),32=>array('分句连接','POSTAG_ID_C_Z'),40=>array('副词','POSTAG_ID_D'),41=>array('副词("不")','POSTAG_ID_D_B'),42=>array('副词("没")','POSTAG_ID_D_M'),50=>array('叹词','POSTAG_ID_E'),60=>array('方位词','POSTAG_ID_F'),61=>array('方位短语(处所词+方位词)','POSTAG_ID_F_S'),62=>array('方位短语(名词+方位词“地上”)','POSTAG_ID_F_N'),63=>array('方位短语(动词+方位词“取前”)','POSTAG_ID_F_V'),64=>array('方位短语(动词+方位词“取前”)','POSTAG_ID_F_Z'),70=>array('前接成分','POSTAG_ID_H'),71=>array('数词前缀(“数”---数十)','POSTAG_ID_H_M'),72=>array('时间词前缀(“公元”“明永乐”)','POSTAG_ID_H_T'),73=>array('姓氏','POSTAG_ID_H_NR'),74=>array('姓氏','POSTAG_ID_H_N'),80=>array('后接成分','POSTAG_ID_K'),81=>array('数词后缀(“来”--,十来个)','POSTAG_ID_K_M'),82=>array('时间词后缀(“初”“末”“时”)','POSTAG_ID_K_T'),83=>array('名词后缀(“们”)','POSTAG_ID_K_N'),84=>array('处所词后缀(“苑”“里”)','POSTAG_ID_K_S'),85=>array('状态词后缀(“然”)','POSTAG_ID_K_Z'),86=>array('状态词后缀(“然”)','POSTAG_ID_K_NT'),87=>array('状态词后缀(“然”)','POSTAG_ID_K_NS'),90=>array('数词','POSTAG_ID_M'),95=>array('名词','POSTAG_ID_N'),96=>array('人名(“毛泽东”)','POSTAG_ID_N_RZ'),97=>array('机构团体(“团”的声母为t，名词代码n和t并在一起。“公司”)','POSTAG_ID_N_T'),98=>array('','POSTAG_ID_N_TA'),99=>array('机构团体名("北大")','POSTAG_ID_N_TZ'),100=>array('其他专名(“专”的声母的第1个字母为z，名词代码n和z并在一起。)','POSTAG_ID_N_Z'),101=>array('名处词','POSTAG_ID_NS'),102=>array('地名(名处词专指：“中国”)','POSTAG_ID_NS_Z'),103=>array('n-m,数词开头的名词(三个学生)','POSTAG_ID_N_M'),104=>array('n-rb,以区别词/代词开头的名词(该学校，该生)','POSTAG_ID_N_RB'),107=>array('拟声词','POSTAG_ID_O'),108=>array('介词','POSTAG_ID_P'),110=>array('量词','POSTAG_ID_Q'),111=>array('动量词(“趟”“遍”)','POSTAG_ID_Q_V'),112=>array('时间量词(“年”“月”“期”)','POSTAG_ID_Q_T'),113=>array('货币量词(“元”“美元”“英镑”)','POSTAG_ID_Q_H'),120=>array('代词','POSTAG_ID_R'),121=>array('副词性代词(“怎么”)','POSTAG_ID_R_D'),122=>array('数词性代词(“多少”)','POSTAG_ID_R_M'),123=>array('名词性代词(“什么”“谁”)','POSTAG_ID_R_N'),124=>array('处所词性代词(“哪儿”)','POSTAG_ID_R_S'),125=>array('时间词性代词(“何时”)','POSTAG_ID_R_T'),126=>array('谓词性代词(“怎么样”)','POSTAG_ID_R_Z'),127=>array('区别词性代词(“某”“每”)','POSTAG_ID_R_B'),130=>array('处所词(取英语space的第1个字母。“东部”)','POSTAG_ID_S'),131=>array('处所词(取英语space的第1个字母。“东部”)','POSTAG_ID_S_Z'),132=>array('时间词(取英语time的第1个字母)','POSTAG_ID_T'),133=>array('时间专指(“唐代”“西周”)','POSTAG_ID_T_Z'),140=>array('助词','POSTAG_ID_U'),141=>array('定语助词(“的”)','POSTAG_ID_U_N'),142=>array('状语助词(“地”)','POSTAG_ID_U_D'),143=>array('补语助词(“得”)','POSTAG_ID_U_C'),144=>array('谓词后助词(“了、着、过”)','POSTAG_ID_U_Z'),145=>array('体词后助词(“等、等等”)','POSTAG_ID_U_S'),146=>array('助词(“所”)','POSTAG_ID_U_SO'),150=>array('标点符号','POSTAG_ID_W'),151=>array('顿号(“、”)','POSTAG_ID_W_D'),152=>array('句号(“。”)','POSTAG_ID_W_SP'),153=>array('分句尾标点(“，”“；”)','POSTAG_ID_W_S'),154=>array('搭配型标点左部','POSTAG_ID_W_L'),155=>array('搭配型标点右部(“》”“]”“）”)','POSTAG_ID_W_R'),156=>array('中缀型符号','POSTAG_ID_W_H'),160=>array('语气词(取汉字“语”的声母。“吗”“吧”“啦”)','POSTAG_ID_Y'),170=>array('及物动词(取英语动词verb的第一个字母。)','POSTAG_ID_V'),171=>array('不及物谓词(谓宾结构“剃头”)','POSTAG_ID_V_O'),172=>array('动补结构动词(“取出”“放到”)','POSTAG_ID_V_E'),173=>array('动词“是”','POSTAG_ID_V_SH'),174=>array('动词“有”','POSTAG_ID_V_YO'),175=>array('趋向动词(“来”“去”“进来”)','POSTAG_ID_V_Q'),176=>array('助动词(“应该”“能够”)','POSTAG_ID_V_A'),180=>array('状态词(不及物动词,v-o、sp之外的不及物动词)','POSTAG_ID_Z'),190=>array('语素字','POSTAG_ID_X'),191=>array('名词语素(“琥”)','POSTAG_ID_X_N'),192=>array('动词语素(“酹”)','POSTAG_ID_X_V'),193=>array('处所词语素(“中”“日”“美”)','POSTAG_ID_X_S'),194=>array('时间词语素(“唐”“宋”“元”)','POSTAG_ID_X_T'),195=>array('状态词语素(“伟”“芳”)','POSTAG_ID_X_Z'),196=>array('状态词语素(“伟”“芳”)','POSTAG_ID_X_B'),200=>array('不及物谓词(主谓结构“腰酸”“头疼”)','POSTAG_ID_SP'),201=>array('数量短语(“叁个”)','POSTAG_ID_MQ'),202=>array('代量短语(“这个”)','POSTAG_ID_RQ'),210=>array('副形词(直接作状语的形容词)','POSTAG_ID_AD'),211=>array('名形词(具有名词功能的形容词)','POSTAG_ID_AN'),212=>array('副动词(直接作状语的动词)','POSTAG_ID_VD'),213=>array('名动词(指具有名词功能的动词)','POSTAG_ID_VN'),230=>array('空格','POSTAG_ID_SPACE'),);
        return isset($postTag[$tag]) ? $postTag[$tag] : false;
    }
}

?>