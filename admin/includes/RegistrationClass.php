<?php
require_once("config.php");
require_once("DatabaseTransaction.php");
require_once('Reusefunctions.php');

class Registration extends resuseFunctions

{
	var $uid;
	var $entry_date;
	var $username;
	var $Email;
	var $password;
	var $type_id;
        
    public function __construct(){
        parent::__construct();        
    }
    
    public function InsertUser()
	{
            $values = array
                (
                     "name" => $this->_request['name'],
                     "gender"=>$this->_request['gender'],		 
                     "email"=>$this->_request['email'],
                     "password"=>md5($this->_request['password']),
                     "designation"=>$this->_request['designation'],
					 "status"=>$this->_request['status'],
                     "user_type"=>$this->_request['user_type']
                );
				//echo "ssssssssss<pre>";print_r($this->_request,0);die;
            $table='users';
            $this->_request['id'] =Mysql::insert($values,$table);            
            
            $return_array = array();
            $return_array['error']='';
            $return_array['success']='';
			
			//Add relationships in group and user table
			if(isset($this->_request['id']) && !empty($this->_request['id'])){
			$groups_to_be_added=$this->_request['groups'];
			$cnt=0;
			foreach($groups_to_be_added as $group)
			{			
			   $values1 = array
					(
						 "group_id" => $group,
						 "user_id"=>$this->_request['id']                     
					);
				$table1='users_groups';
				$insert_id =Mysql::insert($values1,$table1);
				if(empty($insert_id))
				{
					$cnt+=$cnt;
				}
			}
			}
			$return_array['id']=$this->_request['id'];
            if(isset($this->_request['id']) && !empty($this->_request['id'])){
                $return_array['success'] = ($cnt<=0)?"User successfully registered":"User successfully registered but group not get added.";				
				
            }else{
                $return_array['error'] = "Something went wrong please contact to Administrator";   
            }      
            
            return $return_array;
	}
    public function UpdateUser()
	{
				//If user enter password while updating then only update
				$password="";
				if($this->_request['password']<>''){
					$password=md5($this->_request['password']);
					$password=", password='".$password."'";
				}
				
			$where=" WHERE id='".$this->_request['uid']."'";
            $sql="UPDATE users SET name='".$this->_request['name']."', gender='".$this->_request['gender']."',email='".$this->_request['email']."',designation='".$this->_request['designation']."', user_type=".$this->_request['user_type'].$password.$where;
            $this->_request['id'] =Mysql::raw_sql($sql);            

			//get existing groups of user
			$existing_groups=array();
			$existing_groups_array =Mysql::getdataAll("users_groups",array("group_id"),array("is_deleted"=>0,"user_id"=>$this->_request['uid']));
			foreach($existing_groups_array as $group){
			$existing_groups[]=$group['group_id'];}
			
			//echo "saxxxxxxxxxxxxxxxxxxxxxxxxxsaww<pre>";print_r($existing_groups,0);exit;
			$users_selection_groups=array();
			
			if(!empty($this->_request['groups'])){
			$users_selection_groups=$this->_request['groups'];}
			
			$a=$existing_groups;
			$b=$users_selection_groups;
			
			$remove_groups=array_diff($a, $b);
			$groups_to_be_added=array_diff($b, $a);
			//echo "saxxxxxxxxxxxxxxxxxxxxxxxxxsa<pre>";print_r($groups_to_be_added,0);die;
			//delete unselected groups
			if(!empty($remove_groups))
			{
				$sql_del="UPDATE users_groups SET is_deleted=1 WHERE user_id=".$this->_request['uid']." AND is_deleted=0 AND group_id in (".@implode(",",$remove_groups).")";
				$del_res=Mysql::raw_sql($sql_del);
			}
			
			//Add new selected group
		    if(!empty($groups_to_be_added) && $groups_to_be_added[0]<>''){
				foreach($groups_to_be_added as $group_add)
				{			
				   $values1 = array
						(
							 "group_id" => $group_add,
							 "user_id"=>$this->_request['uid']                     
						);
					$table1='users_groups';
					$insert_id =Mysql::insert($values1,$table1);
				}
			}			
            $return_array['success'] = "Information has been updated";
			$return_array['id']=$this->_request['uid'];
            return $return_array;
	}
	
	public function InsertGroup()
	{
            $values = array
                (
                     "name" => $this->_request['name'],
                     "description"=>$this->_request['description']	 
				);
            $table='groups';
            $this->_request['id'] =Mysql::insert($values,$table);            
            
            $return_array = array();
            $return_array['error']='';
            $return_array['success']='';
			$return_array['id']=$this->_request['id'];			
            if(isset($this->_request['id']) && !empty($this->_request['id'])){
                $return_array['success'] = "Group added successfully.";
				
            }else{
                $return_array['error'] = "Something went wrong please contact to Administrator";   
            }      
            
            return $return_array;
	}
    public function Updategroup()
	{
				
			$where=" WHERE id='".$this->_request['gid']."'";
            $sql="UPDATE groups SET name='".$this->_request['name']."', description='".$this->_request['description']."'".$where;
            $this->_request['id'] =Mysql::raw_sql($sql);            
		
            $return_array['success'] = "Information has been updated";
			$return_array['id']=$this->_request['gid'];
            return $return_array;
	}	
    public function LoginUser()
    {
        $return_message['login_success'] = false;
        $return_message['error'] = '';
        $return_message['is_error'] = 0;
        $is_validation_error='';
        if(empty($this->_request['username']) && empty($this->_request['password'])){
            $return_message['is_error'] = 1;
            return $return_message['error'] = 'Please enter login credentials.';
        }
        else if(empty($this->_request['username']))
        {
            $return_message['is_error'] = 1;
            return $return_message['error'] = 'Please enter a Username.'; 
        }else if(empty($this->_request['password'])){
            $return_message['is_error'] = 1;
            return $return_message['error'] = 'Please enter password.';
        }
        
        $fields = array('id','name','status','user_type','is_deleted');
        $condition = array("name" => $this->_request['username'],
            'password' => md5($this->_request['password']),
            'is_deleted' => 0,
			'status'=>1
            );        
        $resultArray=Mysql::getdata("users",$fields,$condition); 
           
        if(!empty($resultArray)){
                
                //Set loggedin user's session 
                $_SESSION['userID'] = $resultArray['id'];
                $_SESSION['alogin'] = $resultArray['name'];
                $_SESSION['status'] = $resultArray['status'];
                $_SESSION['is_admin'] = $resultArray['user_type']; //user_type 1=admin, user_type=0 Normal user
                $return_message['is_error'] = 0;
                $return_message['login_success'] = true;
                $return_message['redirection_file'] = 'dashboard.php';
        }else{
               $return_message['is_error'] = 1;
               $return_message['error'] = 'Invalid login credentials.';    
        }
        //echo "sasa".print_r($_SESSION,0);exit;
        return $return_message;
    }
	
    public function Logout()
    {
        session_destroy();
        @header ('Location:index.php');
        exit;
    }
    public function check_required_field()
    {
        
        $is_required = false;
        $return_array =array();
        $return_array['success_response']='';
        $return_array['error']='';
        $return_array['success']='';
        $return_array['is_error']=0;
        $return_array['partially_error']='';
                
        //Required fields check
        if(isset($this->_request['username']) && empty($this->_request['username']))
            $is_required=true;
        else if(isset($this->_request['password']) && empty($this->_request['Password']))
            $is_required=true;
        

        if($is_required){
            return $return_array['error'] = 'Please enter required fields';
            $return_array['is_error']=1;
        }
        
       
        //Validate fields
        if (!filter_var($this->_request['Email'], FILTER_VALIDATE_EMAIL)) {
            $return_array['error'] = 'Please enter valid email address'; 
            $return_array['is_error']=1;
        }
        else if(!in_array ($this->_request['user_type'], array("Sales","Operations"))){
            $return_array['error'] = 'Please select valid user type'; 
            $return_array['is_error']=1;
        }
        else if($this->check_user_exists($this->_request['username']))
        {
            $return_array['success_response'] = 'This user already exists.';
            $return_array['is_error']=1;
        }
        
        return $return_array;
    }//	
 
 public function check_user_exists($userName)
    {
        $fields = array('id');
        $condition = array("name"=>$userName,"is_deleted"=>0);
        $user_result=Mysql::getdata("users",$fields,$condition); 
        //echo 'hi'; print_r($user_result);die;
        if(empty($user_result))
            return false;
        else
            return true;
    }  
  public function dashboard()
  {
		$sql="SELECT id FROM users where is_deleted=0";
        $dashboard_result['users_count']=Mysql::raw_sql_count($sql); 
		
		$sql="SELECT id FROM users where is_deleted=1";
        $dashboard_result['deleted_users_count']=Mysql::raw_sql_count($sql); 	
		
		$sql="SELECT id FROM groups where is_deleted=0";
        $dashboard_result['groups_count']=Mysql::raw_sql_count($sql); 
		
		$sql="SELECT id FROM groups where is_deleted=1";
        $dashboard_result['deleted_groups_count']=Mysql::raw_sql_count($sql);
		return $dashboard_result;
  }
 public function getGroupNames($user_id)
 {
		$sql= "SELECT g.id,g.name FROM groups as g INNER JOIN users_groups as u_g ON g.id=u_g.group_id WHERE u_g.is_deleted=0 AND user_id='".$user_id."'";
        $resultArray=Mysql::raw_sql($sql);
		return $resultArray;
}  
 public function userAction($table,$where)
 {
        $result=Mysql::softDelete($table,$where);
		return $result;
} 
 public function getAllgroups()
 {
		$fields= array('id','name','description');
        $condition = array("is_deleted" => 0);        
        $allgroups=Mysql::getdataAll("groups",$fields,$condition); 
		return $allgroups;
} 
}
?>