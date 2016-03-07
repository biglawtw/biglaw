<?php

	class lib_sqlite{

		private $_host;
		private $_user;
		private $_pass;
		private $_dbna;
		private $_conn;

		private $num;
		private $data;

		public $sql;
		public $query;

		public function __construct($config){
			$this->_dbna = $config["database"];
			$this->_conn = new SQLite3($this->_dbna);
			if($this->_conn->connect_error){
				die("Error to connect to sql server.");
			}
		}

		public function query($sql){
			$this->sql = $sql;
			$this->query = $this->_conn->query($this->sql);
            $this->num = 0;
            while($rs = $this->query->fetchArray()) $this->num++;
            $this->query = $this->_conn->query($this->sql);
            Log::write("--(query) SQL Statement:" . $sql);
			return $this->query;
		}

        public function exec($sql){
            $this->sql = $sql;
            $this->num = 0;
            Log::write("--(exec) SQL Statement:" . $sql);
            return $this->_conn->exec($this->sql);
        }

		public function getNum(){
			return $this->num;
		}

		public function getData(){
			return $this->query->fetchArray();
		}


		public function insert($table, $data){
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
            $this->_conn->query($sql);
            Log::write("--(insert) SQL Statement:" . $sql);
            return $this->_conn->lastInsertRowID();
		}

		public function delete($table , $id){
			$sql = "delete from `$table` WHERE `id`=$id;";
			$this->_conn->query($sql);
            Log::write("--(delete) SQL Statement:" . $sql);
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
            $this->_conn->query($sql);
            Log::write("--(update) SQL Statement:" . $sql);
        }

        public function getAllData($table){
            $this->sql = "SELECT * from `" . $table . "`";
            $this->query($this->sql);
            $result = array();
            if($this->getNum()>0){
                while($rs = $this->query->fetchArray()){
                    $result[] = $rs;
                }    
            }
            Log::write("--(getAllData) SQL Statement:" . $sql);
            return $result;
        }

        /**
         * 取得查詢結果
         * @return Array 查詢結果
         */
        public function getDatas(){
        	$result = array();
        	if($this->getNum()>0){
        		while($rs = $this->query->fetchArray()){
        			$result[] = $rs;
        		}
        	}
        	return $result;
        }
	}
