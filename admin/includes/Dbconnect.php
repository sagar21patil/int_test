<?php
require_once("config.php");
class Dbconnectivity
{

	public function Dbconnect()
	{
	try{
		$cn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME)or die("Error".mysql_error());
		return $cn;
		}
		catch (PDOException $e)
		{
		exit("Error: " . $e->getMessage());
		}
	}
}

?>
