<?php
/*
 * 数据库类
 * author yuanjiang @2.16.2013
*/

if (!defined('IN_BS'))
{
  die('Hacking attempt');
}

class Mysql
{
 var $con;
 /* 构造函数 */
 function __construct($host,$user,$pass,$dbbase,$charset)
 {
  $this->con  = @mysql_connect($host,$user,$pass) or die('NO SERVER');
  if($this->con)
  {
    mysql_select_db($dbbase,$this->con) or die('NO DATABASE : '.$dbbase);
    mysql_query('set names '.$charset);
  }
 }

 /**
  * 执行sql语句
  * @param string $sql 需要执行的sql
  * @return obj
  * */

 function query($sql)
 {
  return mysql_query($sql,$this->con);
 }


 /*取得查询数量*/
 function getCount($sql)
 {
  $result=mysql_num_rows($this->query($sql));
  return $result;
 }

 
 /**
  * 取一条查询结果
  * @param string $sql 需要执行的sql
  * @return array array('id'=>'1','dd'=>'2');
  **/

 function getRow($sql)
 {
  $result = $this->query($sql);
  return mysql_fetch_assoc($result);
 } 

 
 /**
  * 取一条结果首字段值
  * @param string $sql 需要执行的sql
  * @return string
  **/

 function getOne($sql)
 {
  $result = $this->query($sql);
  $result =  mysql_fetch_array($result);
  return $result[0];
 }

 
 /**
  * 返回结果集
  * @param string $sql 需要执行的sql
  * @param string $filed 需要作为索引的字段
  * @return array  array(array('id'=>'1','dd'=>'2'),array('id'=>'1','dd'=>'2'));
  * */

 function getAll($sql)
 {
 $res = $this->query($sql);
        if ($res !== false)
        {
            $arr = array();
            while ($row = mysql_fetch_assoc($res))
            {
                $arr[] = $row;
            }
            return $arr;
        }
        else
        {
            return false;
        }
 }

 
 /**
  * 取新增ID
  * @return int
  * */
 function get_insertid()
 {
  return mysql_insert_id();
 }


 /**
  * 单个数据表插入
  * @param array $Data array('字段1'=>'值','字段2'=>'值',)
  * @param string $table 表名
  * */
 function insert($Data,$table)
 {
  //insert into table (字段，字段) values(值，值)
  $key_array = implode(',',array_keys($Data));
  $key_val   = '\''.implode('\',\'',array_values($Data)).'\'';
  $sql       = "insert into ".$table." ($key_array) values($key_val)";
  return $this->query($sql);
 }
 

 /**
  * 更新单表
  * @param array $Data array('字段1'=>'值','字段2'=>'值',)
  * @param array $where array('字段1'=>'值','字段2'=>'值',)需要更新的 条件
  * @param string $table 表名www.2cto.com
  * */
 function update($Data,$table,$where)
 {
  //update table set 字段=值，字段=值 where key =value;
  $key_var = array();
  foreach($Data as $key=>$val)
  {
    $key_var[] = $key."='".$val."'";
  }
  $key_var = implode(',',$key_var);
  $whe_var = array();
  foreach($where as $key=>$val)
  {
   $whe_var[] = $key."='".$val."'";
  }
  $whe_var = implode(' and ',$whe_var); 
  if($whe_var)
  {
   $whe_var = ' where '.$whe_var;
  }
  $sql = "update ".$table." set ".$key_var.$whe_var;
  return $this->query($sql);
  //return $sql;
 }

//删除数据
function delete($key,$value,$table)
{
  $sql="delete from  ".$table." where ".$key."=".$value."";
  return $this->query($sql);
}
 
} 
?>