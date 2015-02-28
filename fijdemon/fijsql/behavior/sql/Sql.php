<?php
/*
 * 所有数据库类的基类
 */
namespace fijdemon\fijsql\behavior\sql;

/**
 * 数据库通用方法
 +--------------------------------------------------
 * @author Administrator
 * @createTime 2015年02月07日(星期六)
 +--------------------------------------------------
 */
abstract class Sql implements SqlInterface{
    abstract public function connect($host,$port,$user,$password,$db,$charset='utf-8');
    
    protected $backUp = array();
    
    public function limit($limit, $length = null){
        // 如果是字符串就直接赋值
        if(is_string($limit)){
            $this->backUp['limit'] = $limit;
            return $this;
        }else 
        // 如果limit是数字
        if(is_int($limit)){
            // 如果 $length 是 null 就 0,$limit
            if($length === null){
                $this->backUp['limit'] = "0, {$limit}";
                return $this;
            }else 
            // 如果 $length 是数字 就 $limit, $length
            if(is_int($length)){
                $this->backUp['limit'] = "{$limit}, {$length}";
                return $this;
            }
        }
        fijsql_exception("limit无法解析", 500, __FILE__);
    }

    public function field($field){
        // 字符串直接赋值
        if(is_string($field)){
            $this->backUp['field'] = $field;
            return $this;
        }else
        // 如果是数组就直接拆分
        if(is_array($field)){
            $this->backUp['field'] = implode(",", $field);
            return $this;
        }
        fijsql_exception("field无法解析", 500, __FILE__);
    }

    abstract public function update();

    public function set($data){
        // 如果是字符串就直接赋值
        if(is_string($data)){
            $this->backUp['set'] = $data;
            return $this;
        }else 
        // 如果是数组就转换
        if(is_array($data)){
            $_a = array();
            foreach ($data as $k => $v){
                $_v = is_numeric($v) ? $v : "'{$v}'";
                $_a[] = "{$k}={$_v}";
            }
            $this->backUp['set'] = implode(",", $_a);
            return $this;
        }
        fijsql_exception("set无法解析", 500, __FILE__);
    }
    
    abstract public function insertAll(Array $data);

    abstract public function select();

    abstract public function query($sqlStr);

    abstract public function delete();

    public function table($table){
        if(is_string($table)){
            $this->backUp['table'] = $table;
            return $this;
        }
        fijsql_exception("table无法解析",500,__FILE__);
    }

    public function group($group){
        if(is_string($group)){
            $this->backUp['group'] = "by {$group}";
            return $this;
        }
        fijsql_exception("group无法解析",500,__FILE__);
    }

    public function where($where){
        // 如果是字符串直接赋值
        if(is_string($where)){
            $this->backUp['where'] = $where;
            return $this;
        }else 
        // 如果是数组，那麻烦了！
        if(is_array($where)){
            // map 模式
            if(count($where)==1){
                $this->backUp['where'] = $this->_sqlWhereArrayDeal($where);dump($this->backUp['where'],1);
            // 普通 and 模式
            }else{
                $_w = array();
                foreach ($where as $k => $v){
                    $_w[] = "`{$k}`=".(is_numeric($v) || $v=='NOW()' ? $v : "'{$v}'");
                }
                $this->backUp['where'] = implode(" AND ", $_w);
            }
            return $this;
        }
        fijsql_exception("where无法解析",500,__FILE__);
    }
    private function _sqlWhereArrayDeal($where,$how=null){
        // 如果有and，处理and
        if(isset($where['and'])){
            $where['and'] = $this->_sqlWhereArrayDeal($where['and'], ' AND ');
        }
        // 如果有or，处理or
        if(isset($where['or'])){
            $where['or'] = $this->_sqlWhereArrayDeal($where['or'], ' OR ');
        }
        return '('.implode(')'.$how.'(', $where).')';
    }

    abstract public function insert(array $data);
    
    public function having($having){
        // 如果是数字直接赋值
        if(is_string($having)){
            $this->backUp['having'] = $having;
            return $this;
        }else 
        // 如果是数组，那麻烦了！
        if(is_array($having)){
            $this->backUp['having'] = $this->_sqlWhereArrayDeal($having);
            return $this;
        }
        fijsql_exception("having无法解析",500,__FILE__);
    }
    
    public function order($order){
        if (is_string($order)){
            $this->backUp['order'] = $order;
            return $this;
        }
        fijsql_exception("order无法解析",500,__FILE__);
    }
}

?>