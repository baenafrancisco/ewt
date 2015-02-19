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
			}catch(PDOException $error){
	    		echo $error->getMessage();
			}
		}

		public function select($database_table, $values=array(), $where=false){
			/*
			Selects values from the database, default = '*'
			*/
			
			if (count($values)>0){ $format_values = join(', ', $values); } else { $format_values = "*";}

			$sql= 'SELECT '.$format_values.' FROM '. $database_table . ((!$where)?'':' WHERE ' . $where) . ';';
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			return $result;
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