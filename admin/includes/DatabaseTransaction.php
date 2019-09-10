<?php 
require_once('config.php');
require_once("Dbconnect.php");

class MySql
{
	//This function is used to insert record into DB just passing fiels and condition in dynamic array
	public static function insert($values,$table)
	{
		if(empty($values)|| empty($table))
		{
			return "";
		}
		$listFields=array();
		$listvalue=array();
		foreach($values as $k=>$v)
		{
		$listFields[]="".$k."";
		$listvalue[]="'".$v."'";
		
		}
		
		$listFields=implode(",",$listFields);
		$listvalue=implode(",",$listvalue);

		$query="INSERT INTO ".$table." (".$listFields.") VALUES "." (".$listvalue.") ";
		$cn=Dbconnectivity::Dbconnect();
		$res=mysqli_query($cn,$query);
                
		return mysqli_insert_id($cn);
	
	}
	//This function is used to soft delete records
	public static function softDelete($table,$where=null)
	{
            if(empty($table)||empty($where))
            {
                    return "";
            }		  
		$wherecondition = array();
		foreach($where as $k=>$v){
		$wherecondition[]="".$k."='".$v."'";
		}
		
		$query = "UPDATE ".$table." set is_deleted=1 WHERE ".implode(" AND ",$wherecondition);
		//echo  $query ;die;
		 $cn=Dbconnectivity::Dbconnect();
	 
	  if (mysqli_query($cn, $query)) {
		return true;
	   } else {
		  return false;
	   };
	}
	//If get only one record
	public static function getdata($table,$fields,$where=null)
	{
	  if(empty($table)||empty($where) ||empty($fields))
	  {
		  return "";
	  }		  
	$wherecondition = array();
	foreach($where as $k=>$v){
	$wherecondition[]="".$k."='".$v."'";
	}

	 $query = "SELECT ".implode(",",$fields)." FROM ".$table." WHERE ".implode(" AND ",$wherecondition);
	 $cn=Dbconnectivity::Dbconnect();
	 $res=mysqli_query($cn,$query);
	 $result=mysqli_fetch_assoc($res);
	 return $result;
	}
     //Get all resultset of perticular conditions   
	public static function getdataAll($table,$fields,$where=null)
	{
	  if(empty($table)||empty($where) ||empty($fields))
	  {
		  return "";
	  }			  
            $wherecondition = array();
            foreach($where as $k=>$v){
            $wherecondition[]="".$k."='".$v."'";
            }
			 $cn=Dbconnectivity::Dbconnect();
            $query ="SELECT ".implode(",",$fields)." FROM ".$table." WHERE ".implode(" AND ",$wherecondition);
			//echo $query;
             $res=mysqli_query($cn,$query);	
             $resultAll = array();
             while($result=mysqli_fetch_assoc($res))
             {
                $resultAll[]=$result;

             }
			 
		return $resultAll;
	}
	//This function is usefull for execute any custom queries
	public static function raw_sql($sql)
	{
	  if(empty($sql))
	  {
		  return "";
	  }			  
			 $cn=Dbconnectivity::Dbconnect();            
             $res=mysqli_query($cn,$sql);	
             $resultAll = array();
             while($result=mysqli_fetch_assoc($res))
             {
                $resultAll[]=$result;

             }
		return $resultAll;
	}
	
	//Get count of record
	public static function raw_sql_count($sql)
	{
	  if(empty($sql))
	  {
		  return "";
	  }			  
			 $cn=Dbconnectivity::Dbconnect();            
             $res=mysqli_query($cn,$sql);
			//echo '<pre>';print_r($res,0);
			 return (!empty($res))?$res->num_rows:0;
	}   
		
}

?>
