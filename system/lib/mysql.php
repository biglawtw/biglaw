<?php

    class lib_mysql{
        
        private $_host;
        private $_user;
        private $_pass;
        private $_dbna;

        private $_conn;

        public $sql;
        public $query;

        private $num;
        private $data;

        public function __construct($config){
            $this->_host = $config['hostname'];
            $this->_user = $config['username'];
            $this->_pass = $config['password'];
            $this->_dbna = $config['database'];

            $this->_conn = mysql_connect($this->_host , $this->_user , $this->_pass);
            mysql_select_db($this->_dbna , $this->_conn);
            mysql_query("SET NAMES utf8");
        }

        public function query($sql){
            $this->sql = $sql;
            $this->query = mysql_query($this->sql , $this->_conn);
            Log::write("--(query) SQL Statement:" . $sql);
            return $this->query;
        }
        public function getNum(){
            $this->num = mysql_num_rows($this->query);
            return $this->num;
        }
        public function getData(){
            return mysql_fetch_array($this->query);
        }
        public function insert($table , $data){
            
            $sql = "insert into `$table`(";
            $sql2 = "";
            foreach($data as $key => $value){
                $sql .= "`$key`,";
                $sql2 .= "'$value',";
            }
            $sql = substr($sql , 0 , -1);
            $sql .= ") VALUES(";
            $sql .= $sql2;
            $sql = substr($sql , 0 , -1);
            $sql .=');';
            $this->query($sql);
            Log::write("--(exec) SQL Statement:" . $sql);
            return mysql_insert_id($this->_conn);
        }
        public function delete($table , $id){
            $sql = "delete from `$table` WHERE `id`=$id;";
            Log::write("--(exec) SQL Statement:" . $sql);
            mysql_query($sql);
        }
        public function update($table , $data){
            $sql = "UPDATE `$table` SET ";
            foreach($data as $key => $value){
                if($key!='id'){
                    $sql .= " `$key`='$value' ,";
                }
            }
            $sql = substr($sql , 0 , -1);
            $sql .= " WHERE `id` = ".$data['id'];
            Log::write("--(exec) SQL Statement:" . $sql);
            mysql_query($sql , $this->_conn);
        }
        public function getAllData($table){
            $this->sql = "SELECT * from `" . $table . "`";
            $this->query = mysql_query($this->sql , $this->_conn);
            $this->num = mysql_num_rows($this->query);
            $result = array();
            if($this->num>0){
                while($rs = mysql_fetch_assoc($this->query)){
                    $result[] = $rs;
                }    
            }
            Log::write("--(exec) SQL Statement: SELECT * from `" . $table . "`");
            return $result;
        }

        /**
         * 取得查詢結果
         * @return Array 查詢結果
         */
        public function getDatas(){
            $result = array();
            if($this->getNum()>0){
                while($rs = mysql_fetch_array($this->query)){
                    $result[] = $rs;
                }
            }
            return $result;
        }


    }
    
?>