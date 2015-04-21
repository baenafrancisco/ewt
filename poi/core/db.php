<?php
	
	// Database credentials
	const DB_HOST = "localhost";
	const DB_NAME = "fbaenarodriguez";
	const DB_USERNAME = "fbaenarodriguez";
	const DB_PASSWORD = "sheethoh";

	class DBManager{

		/* 
		DBConnection: This class holds information about a DBConnection

		Fields
		  - $connection: holds a PDO object
		*/
		private $connection;

		function __construct(){
			try{
				$this->connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';',
					DB_USERNAME,DB_PASSWORD);

				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $error){
	    		echo "Connection with the database couldn't be established. Contact the system administrator.";
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
			Selects values from the database

			Parameters
			  - $database_table: name of the database table to use (required)
			  - $values = array of columns to fetch. (default: '*' (all))
			  - $where = where statement (default: false)

			Returns:
				Array indexed by column name.
			*/
			try{
				$format_values = join(', ', $values);
				$sql= 'SELECT '.$format_values.' FROM '. $database_table . ((!$where)?'':' WHERE ' . $where) . ';';
				$stmt = $this->connection->prepare($sql);
				$stmt->execute();
				$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e){
				$response = null;
			}
			return $response;
		}


		public function insert($database_table, $values=array()){
			/*
			Insert values into a database table

			Parameters
			  - $database_table: name of the database table to use (required)
			  - $values = assoc array of columns and values (required)

			Returns
				true: if everything worked.
				false: if no $values specified, or any error.
			*/
			$response = true;
			if (count($values)>0){
				try{
					$cols = array_keys($values);
					$vals = array_values($values);
					$columns = join(', ', $cols);
					// Prepared STM in order to avoid security risks
					$prepared_vals = $this->create_prepare_stm(', ',$cols, false);
					$sql = 'INSERT INTO '. $database_table .' ('.$columns.') VALUES ('.$prepared_vals.');';
					$statement = $this->connection->prepare($sql);
					$statement->execute($values);
				} catch (PDOException $e){
					$response = false;
				}
			} else {
				$response = false;
			}
			return $response;
		}

		public function delete($database_table, $id){
			/*
			Deletes one record 

			Parameters
			  - $database_table: name of the database table to use (required)
			  - $id = id of the element to delete (required)

			Returns
				true: if everything worked.
				false: if the element wasn't deleted or there's any error.
			*/
			try{
				$sql = 'DELETE FROM ' . $database_table . ' WHERE id="' . $id .'" ;';
				$response = $this->connection->exec($sql)==1;
			} catch (PDOException $e){
				$response = false;
			}
			return $response;
		}

		public function exists($database_table, $id){
			/*
			Checks whether an element exists or not in a table

			Parameters
			  - $database_table: name of the database table to use (required)
			  - $id = id of the element to check (required)

			Returns
				true: if the element exists
				false: if the element doesn't exist or there's any error
			*/

			try{
				$sql = 'SELECT EXISTS(SELECT 1 FROM ' . $database_table . ' WHERE id="' . $id .'") AS E;';
				$stmt = $this->connection->prepare($sql);
				$stmt->execute();
				$response = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['E']==1;

			} catch(PDOException $e){
				$response = false;
			}
			return $response;
		}

		public function RAWQuery($query){
			/*
			Executes a RAW query and returns the result.
			
			Parameters
			  - $database_table: name of the database table to use (required)
			  - $id = id of the element to check (required)

			Returns
				A PDO Statement object.

			ATTENTION: queryes made by the RAWQuery statement needs to be checked.

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