<?php
	
	// Database credentials
	const DB_HOST = "localhost";
	const DB_NAME = "fbaenarodriguez";
	const DB_USERNAME = "fbaenarodriguez";
	const DB_PASSWORD = "sheethoh";


	class DBManager{

		/* 
		DBConnection: This class holds information about a DBConnection 
		*/
		private $connection;

		function __construct(){
			try{
				$this->connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';',
					DB_USERNAME,DB_PASSWORD);

				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $error){
				// This error could be prompt to the user
	    		echo $error->getMessage();
			}
		}

		private function create_prepare_stm($nexus, $columns, $equals=true, $exclude=array()){
			if($equals){
				return join($nexus, array_map(function($col) { return "$col=:$col"; }, $columns));
			} else {
				return join($nexus, array_map(function($col) { return ":$col"; }, $columns));
			}
		}

		public function select($database_table, $values=array('*'), $where=false){
			/*
			Selects values from the database, default = '*'
			*/
			
			$format_values = join(', ', $values);
			$sql= 'SELECT '.$format_values.' FROM '. $database_table . ((!$where)?'':' WHERE ' . $where) . ';';
			$stmt = $this->connection->prepare($sql);

			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}


		public function insert($database_table, $values=array()){
			/*
			Inserts into a table 
			*/
			$cols = array_keys($values);
			$vals = array_values($values);
			$columns = join(', ', $cols);
			// Prepared STM in order to avoid security risks
			$prepared_vals = $this->create_prepare_stm(', ',$cols, false);
			$sql = 'INSERT INTO '. $database_table .' ('.$columns.') VALUES ('.$prepared_vals.');';
			$statement = $this->connection->prepare($sql);
			return $statement->execute($values);
		}

		public function delete($database_table, $id){
			/*
			Delete record by id
			*/
			$sql = 'DELETE FROM ' . $database_table . ' WHERE id="' . $id .'" ;';
			return $this->RAWQuery($sql);
		}

		public function exists($database_table, $id){
			/*
			Delete record by id
			*/
			$sql = 'SELECT EXISTS(SELECT 1 FROM ' . $database_table . ' WHERE id="' . $id .'") AS E;';
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['E']==1;
		}

		public function RAWQuery($query){
			/*
			Executes a RAW query and returns the result
			*/
			return $this->connection->query($query);
		}

		function close(){
			/* 
			Explicitally closes PDO connection. Neeeded?
			*/
			$this->connection = null;
		}



	}
?>