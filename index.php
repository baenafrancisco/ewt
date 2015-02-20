<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>EWT Assessment</title>
</head>
<body>

<?php
include_once "./core/db.php";



echo "Hello world!";
$db = new DBManager();
?> 

<br>
<?

$frankie_data = array(	'username' => 'fran' ,
						'password' => '1234' ,
						'isadmin' => '0' , );
//$db -> insert('poi_users', $frankie_data);

$where = "";

//$db -> delete('poi_users', 5);

print_r($db -> select('poi_users', array('*'), $where));



?>

</body>
</html>