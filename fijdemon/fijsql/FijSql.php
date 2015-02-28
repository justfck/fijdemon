<?php
/*
 * 数据库写入类，写入数据库的方法集合
 */
namespace fijdemon\fijsql;

use fijdemon\fijsql\behavior\sql\SqlInterface;
use fijdemon\fijsql\mysql\SaeMysql;
use fijdemon\fijsql\mysql\LocalMysql;
																
        $path = dirname(__FILE__);
        $spr = DIRECTORY_SEPARATOR;
        // include important files
        require_once "{$path}{$spr}behavior{$spr}sql{$spr}SqlInterface.php";
/**
 * 数据库写入方法插件类入口
 +--------------------------------------------------
 * @author fijdemon
 * @createTime 2015年02月07日(星期六)
 +--------------------------------------------------
 */
class FijSql implements SqlInterface{
    /**
     * 支持的数据库列表
     +--------------------------------------------------
     */
    static private $dbList = array(
        'mysql',
    );
    
    
    /**
     * 当前对象的数据库类型
     +--------------------------------------------------
     */
    private $dbType = 'mysql';
    
    
    /**
     * 数据库操作对象
     +--------------------------------------------------
     * @var unknown
     +--------------------------------------------------
     */
    private $db = null;
    
    
    /**
     * 初始化函数 
     +--------------------------------------------------
     * @param string $dbType 确定数据库类型
     +--------------------------------------------------
     */
    public function __construct($dbType){
        if(in_array(strtolower($dbType),self::$dbList)){
            $this->dbType = strtolower($dbType);
        }

        $path = dirname(__FILE__);
        $spr = DIRECTORY_SEPARATOR;
        // include important files
        require_once "{$path}{$spr}behavior{$spr}common{$spr}fun.php";
        require_once "{$path}{$spr}behavior{$spr}sql{$spr}Sql.php";
        require_once "{$path}{$spr}behavior{$spr}sql{$spr}SqlInterface.php";
        
        
        switch($this->dbType){
            // mysql
            case self::$dbList[0]:
                if(defined('SAE_Success')){
                    require_once "{$path}{$spr}mysql{$spr}SaeMysql.php";
                    $this->db = new SaeMysql();
                }else{echo "local";
                    require_once "{$path}{$spr}mysql{$spr}LocalMysql.php";
                    $this->db = new LocalMysql("robot");
                }
                break;
            default:fijsql_exception("未知数据库类型",500,__FILE__);
        }
        
    }
    
    
	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::query()
     +--------------------------------------------------
     */
    public function query($sqlStr)
    {
        // TODO Auto-generated method stub
        return $this->db->query($sqlStr);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::insert()
     +--------------------------------------------------
     */
    public function insert(array $data)
    {
        // TODO Auto-generated method stub
        return $this->db->insert($data);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::select()
     +--------------------------------------------------
     */
    public function select()
    {
        // TODO Auto-generated method stub
        return $this->db->select();
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::update()
     +--------------------------------------------------
     */
    public function update()
    {
        // TODO Auto-generated method stub
        return $this->db->update();
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::delete()
     +--------------------------------------------------
     */
    public function delete()
    {
        // TODO Auto-generated method stub
        return $this->db->delete();
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::field()
     +--------------------------------------------------
     */
    public function field($field)
    {
        // TODO Auto-generated method stub
        return $this->db->field($field);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::table()
     +--------------------------------------------------
     */
    public function table($table)
    {
        // TODO Auto-generated method stub
        return $this->db->table($table);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::where()
     +--------------------------------------------------
     */
    public function where($where)
    {
        // TODO Auto-generated method stub
        return $this->where($where);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::group()
     +--------------------------------------------------
     */
    public function group($group)
    {
        // TODO Auto-generated method stub
        return $this->group($group);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::having()
     +--------------------------------------------------
     */
    public function having($having)
    {
        // TODO Auto-generated method stub
        return $this->having($having);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::limit()
     +--------------------------------------------------
     */
    public function limit($limit, $length = null)
    {
        // TODO Auto-generated method stub
        return $this->limit($limit,$length);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::order()
     +--------------------------------------------------
     */
    public function order($order)
    {
        // TODO Auto-generated method stub
        return $this->db->order($order);
    }

	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::set()
     +--------------------------------------------------
     */
    public function set($data)
    {
        // TODO Auto-generated method stub
        return $this->db->set($data);
    }
	/* (non-PHPdoc)
     +--------------------------------------------------
     * @see \fijdemon\fijsql\behavior\sql\SqlInterface::insertAll()
     +--------------------------------------------------
     */
    public function insertAll(array $data)
    {
        // TODO Auto-generated method stub
        return $this->db->insertAll($data);
    }


}

?>