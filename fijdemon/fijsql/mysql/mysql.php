<?php
namespace fijdemon\fijsql\mysql;

use fijdemon\fijsql\behavior\sql\Sql;

class mysql extends Sql
{
    /**
     * 保存数据库连接
     +--------------------------------------------------
     */
    private $connect = null;

    
    /**
     * 设置默认数据库
     +--------------------------------------------------
     */
    private $defaultDb = null;
    private $defaultChartset = 'utf8';


    /**
     * 记录执行的sql语句
     +--------------------------------------------------
     * @var array
     +--------------------------------------------------
     */
    private $sqlStr = array();
    
    
    /**
     * 更新方法
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::update()
     +--------------------------------------------------
     */
    public function update()
    {
        $sqlStr = "UPDATE ";
        $sqlStr .= (isset($this->backUp['table']) ? $this->backUp['table'] : fijsql_exception("update未选择数据表",500,__FILE__))." ";
        $sqlStr .= "SET ";
        $sqlStr .= (isset($this->backUp['set'])   ? $this->backUp['set']   : fijsql_exception("未设置更新项",500, __FILE__))." ";
        $sqlStr .= "WHERE ";
        $sqlStr .= (isset($this->backUp['where']) ? $this->backUp['where'] : fijsql_exception("未设置条件",500,__FILE__))." ";
        $this->sqlStr[] = $sqlStr;
        
        $re = mysql_query($sqlStr, $this->connect);
        return $re;
    }

    
    /**
     * 连接
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::connect()
     +--------------------------------------------------
     */
    public function connect($host, $port, $user, $password, $db, $charset = 'utf8')
    {
        $this->connect = @mysql_connect($host.":".$port,$user,$password) or fijsql_exception("数据库连接错误",500,__FILE__);
        $this->defaultDb = $db;
        mysql_select_db($db,$this->connect);
        $this->defaultChartset = $charset;
    }

    
    /**
     * 普通数据库方法
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::query()
     +--------------------------------------------------
     */
    public function query($sqlStr)
    {
        $this->sqlStr[] = $sqlStr;
        return mysql_query($sqlStr, $this->connect);
    }

    
    /**
     * 查找
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::select()
     +--------------------------------------------------
     */
    public function select()
    {
        $sqlStr = 'SELECT ';
        $sqlStr .= (isset($this->backUp['field']) ? $this->backUp['field'] : '*')." ";
        $sqlStr .= "FROM ";
        $sqlStr .= (isset($this->backUp['table']) ? $this->backUp['table'] : fijsql_exception("select未选择数据表",500,__FILE__))." ";
        if(isset($this->backUp['group'])){
            $sqlStr .= "GROUP BY ".$this->backUp['group']." ";
            $sqlStr .= "HAVING ".$this->backUp['having']." ";
        }
        $sqlStr .= "WHERE ";
        $sqlStr .= (isset($this->backUp['where']) ? $this->backUp['where'] : '1')." ";
        $sqlStr .= (isset($this->backUp['limit']) ? "LIMIT ".$this->backUp['limit'] : "")." ";
        $sqlStr .= (isset($this->backUp['order']) ? "ORDER BY ".$this->backUp['order'] : "")." ";
        $this->sqlStr[] = $sqlStr;
        
        $re = mysql_query($sqlStr, $this->connect);
        $msg = array();
        if($re){
            while(!!($m = mysql_fetch_array($re))){
                $msg[] = $m;
            }
        }else{
            return false;
        }
        return $msg;
    }

    
    /**
     * 删除
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::delete()
     +--------------------------------------------------
     */
    public function delete()
    {
        $sqlStr = 'DELETE ';
        $sqlStr .= 'FROM ';
        $sqlStr .= (isset($this->backUp['table']) ? $this->backUp['table'] : fijsql_exception("delete未选择数据表",500,__FILE__))." ";
        $sqlStr .= 'WHERE ';
        $sqlStr .= (isset($this->backUp['where']) ? $this->backUp['where'] : fijsql_exception("delete为选择条件",500,__FILE__))." ";
        $this->sqlStr[] = $sqlStr;
        
        $re = mysql_query($sqlStr, $this->connect);
    }

    
    /**
     * 添加
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::insert()
     +--------------------------------------------------
     */
    public function insert(array $data)
    {
        $sqlStr = "INSERT INTO ";
        $sqlStr .= (isset($this->backUp['table']) ? $this->backUp['table'] : fijsql_exception("insert未选择数据表",500,__FILE__))." ";
        
        $key = $value = array();
        foreach ($data as $kk => $vv) {
            $key[] = "`{$kk}`";
            $value[] = is_numeric($vv) || $vv=='NOW()' ? $vv : "'{$vv}'";
        }
        
        $sqlStr .= ('('.implode(",", $key).')')." ";
        $sqlStr .= "VALUES ";
        $sqlStr .= ('('.implode(",", $value).')')." ";
        $this->sqlStr[] = $sqlStr;

        mysql_query("set names '{$this->defaultChartset}'");
        $re = mysql_query($sqlStr, $this->connect);
        
        return $re !== false ? mysql_insert_id() : false;
    }
	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\Sql::insertAll()
     +--------------------------------------------------
     */
    public function insertAll(Array $data)
    {
        $sqlStr = "INSERT INTO ";
        $sqlStr .= (isset($this->backUp['table']) ? $this->backUp['table'] : fijsql_exception("insert未选择数据表",500,__FILE__))." ";
        
        $key = $value = array();
        foreach ($data as $kk => $vv) {
            foreach ($vv as $k => $v){
                if(!in_array("`{$k}`", $key))$key[] = "`{$k}`";
                $value[$kk][] = is_numeric($v) || $vv=='NOW()' ? $v : "'{$v}'";
            }
            $value[$kk] = "(".implode(",", $value[$kk]).")";
        }
        
        $sqlStr .= ('('.implode(",", $key).')')." ";
        $sqlStr .= "VALUES ";
        $sqlStr .= (implode(",", $value))." ";
        $this->sqlStr[] = $sqlStr;

        mysql_query("set names '{$this->defaultChartset}'");
        $re = mysql_query($sqlStr, $this->connect);
        
        return $re;
    }

}

?>