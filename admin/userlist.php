<?php
require_once("includes/config.php");
	if(strlen($_SESSION['alogin'])==0)
		{	
	@header('location:index.php');
	exit;
	}
	else{
	require_once("includes/DatabaseTransaction.php");	
	require_once('includes/RegistrationClass.php');
	$RegistrationObj = new Registration();

	//Delete User and user's group(s)
	if(isset($_GET['del']) && $_GET['del']<>'')
	{
		$where=array("id"=>$_GET['del']);
		$result=$RegistrationObj->userAction("users",$where);
		
		$where1=array("user_id"=>$_GET['del'],'is_deleted'=>0);
		$result1=$RegistrationObj->userAction("users_groups",$where1);
		
		if($result){
			$_SESSION['msg']="User has been deleted";
		}else{ 	
			$_SESSION['error']="User has not deleted. Please contact Administrator";
		}
		@header('location:userlist.php?para=0');
		exit;
	}
	//Delete users group
	if(isset($_GET['del_grp'])&& $_GET['del_grp']<>''&& isset($_GET['grp_id']))
	{
		$where=array("user_id"=>$_GET['del_grp'],"group_id"=>$_GET['grp_id']);
		$result=$RegistrationObj->userAction("users_groups",$where);
		if($result){
			$_SESSION['msg']="Group has been deleted";
		}else{ 	
			$_SESSION['error']="Group has not deleted. Please contact Administrator";
		}
		@header('location:userlist.php?para=0');
		exit;
	}

	//User confirm/unconfirm
	if((isset($_GET['unconfirm']) && $_GET['unconfirm']<>'') || (isset($_GET['confirm']) && $_GET['confirm']<>''))
	{
		if(isset($_GET['unconfirm']) && $_GET['unconfirm']<>'')
		{
			$status=1;
			$uid=$_GET['unconfirm'];
			$_SESSION['msg']="User has confirmed";
		}else{
			$status=0;
			$uid=$_GET['confirm'];
			$_SESSION['msg']="User has unconfirmed";
		}

		$sql="UPDATE users SET status=".$status." WHERE id=".$uid;
		$resultArray=Mysql::raw_sql($sql);
		
		@header('location:userlist.php?para=0');
		exit;
	}


	//Dispaly message on Users listing page
	if(isset($_SESSION['msg']) || isset($_SESSION['error']))
	{
		$msg=$_SESSION['msg'];
		unset($_SESSION['msg']);
		$error=$_SESSION['error'];
		unset($_SESSION['error']);		
	}	

	//Load active/deleted users depend on page
	if(isset($_GET['para']) && ($_GET['para']==0 || $_GET['para']==1))
	{
		$fields = array('id','name','gender','email','designation','status','user_type','is_deleted');
        $where = array('is_deleted' => $_GET['para']);        
        $resultArray=Mysql::getdataAll('users',$fields,$where);
	}	


 ?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	
	<title>Manage Users</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
  <style>

	.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
	background: #dd3d36;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
	background: #5cb85c;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}

		</style>

</head>

<body>
	<?php include_once('includes/header.php');?>

	<div class="ts-main-content">
		<?php include_once('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title"><?php echo ($_GET['para']==0)? "Manage":"Deleted"?> Users</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">List Users <?php if($_SESSION['is_admin'] && $_GET['para']==0){?> <a href='edit-user.php'><img src='./css/add.png' height='20'width='20'align='right' alt="Add User" title='Add User'></a><?php }?></div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Designation</th>
												<th>User Type</th>
                                                <th>Groups</th>												
                                                <th>Account</th>
											<?php if($_SESSION['is_admin'] && $_GET['para']==0){?> <th>Action</th><?php }?>
										</tr>
									</thead>
									
									<tbody>

<?php 


$cnt=1;
if(!empty($resultArray) > 0)
{
foreach($resultArray as $result)
{	
$user_type=($result['user_type']==0)? 'Normal User': 'Admin User';	
//Get group names of perticular usered assigned to
$groupnames=$RegistrationObj->getGroupNames($result['id']);

		?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>						
                                            <td><?php echo htmlentities($result['name']);?></td>
                                            <td><?php echo htmlentities($result['email']);?></td>
                                            <td><?php echo htmlentities($result['gender']);?></td>
                                            <td><?php echo htmlentities($result['designation']);?> 
                                            <td><?php echo htmlentities($user_type);?></td>
										
                                            <td><ul><?php 
											if(!empty($groupnames))
											{
											foreach($groupnames as $group){?>
											<li>&nbsp; <?php echo $group['name']; if($_SESSION['is_admin']){ ?><a href="userlist.php?del_grp=<?php echo $result['id'] ;?>&grp_id=<?php echo $group['id'] ;?>" onclick="return confirm('Do you want to delete group');" title="Delete group">&nbsp;<i class="fa fa-trash" style="color:red"></i><?php }?></a></li>
											<?php }
												}
												else{ echo "<li>&nbsp;No Group</li>"; }?>
											</ul></td>	
                                            <td>
                                            <?php 
											if($_SESSION['is_admin']  && $_GET['para']==0)
											{ 
												if($result['status'] == 1)
                                                    {?>
                                                    <a href="userlist.php?confirm=<?php echo htmlentities($result['id']);?>" onclick="return confirm('Do you really want to Un-Confirm the Account')">Confirmed <i class="fa fa-check-circle"></i></a> 
                                                    <?php } else {?>
                                                    <a href="userlist.php?unconfirm=<?php echo htmlentities($result['id']);?>" onclick="return confirm('Do you really want to Confirm the Account')">Un-Confirmed </i></a>
                                                    <?php } 
											}else{
												 echo ($result['status'] == 1)? 'Confirmed':'Un-Confirmed';
											}
													?>
</td>
                                            </td>
<?php
if($_SESSION['is_admin']  && $_GET['para']==0)
{
?>											
<td>
<a href="edit-user.php?edit=<?php echo $result['id'];?>" onclick="return confirm('Do you want to Edit User');" title="Edit User">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
<a href="userlist.php?del=<?php echo $result['id'];?>" onclick="return confirm('Do you want to Delete User');" title="Delete User"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
</td>
<?php
}?>
										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script type="text/javascript">
				 $(document).ready(function () {          
					setTimeout(function() {
						$('.succWrap').slideUp("slow");
						$('.errorWrap').slideUp("slow");
					}, 3000);
					});
		</script>
		
</body>
</html>
<?php } ?>
