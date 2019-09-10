<?php
include('includes/config.php');
require_once('includes/RegistrationClass.php');	
if(strlen($_SESSION['alogin'])==0)
	{	
	@header('location:index.php');
	exit;
	}
else{

	$RegistrationObj = new Registration();
	
	//Get all groups for dropdown
	$allGroups=$RegistrationObj->getAllgroups();
	
	
if(isset($_GET['edit']))
	{
		//Get edit user's profile detail
		$editid=$_GET['edit'];
		$fields = array('id','name','status','user_type','gender','designation','email');
        $condition = array("id" => $editid);        
        $result=Mysql::getdata("users",$fields,$condition); 
		
		//Get edit user's groups
		$fields1= array('group_id');
        $condition1 = array("is_deleted" => 0,'user_id'=>$editid);        
        $Userallgroups=Mysql::getdataAll("users_groups",$fields1,$condition1); 
		foreach($Userallgroups as $Userallgroup){$Userallgroupsfiltered[]=$Userallgroup['group_id'];}
	}


if(isset($_POST['submit']))
  {
	//Add/Register user in DB
	if($_POST['action']=='Add')
	{
		$addUser=$RegistrationObj->InsertUser();
		if($addUser['success']<>'')
			$_SESSION['msg']=$addUser['success'];
		else
			$_SESSION['error']=$addUser['error'];
		
		@header('location:edit-user.php?edit='.$addUser['id']);
		exit;		
	}
	else{
		//Update user in DB
		$updateUser=$RegistrationObj->UpdateUser();
		if($updateUser['success']<>'')
			$_SESSION['msg']=$updateUser['success'];
		else
			$_SESSION['error']=$updateUser['error'];
		
		@header('location:edit-user.php?edit='.$updateUser['id']);
		exit;
	}

	}   
//Dispaly message on Users listing page
if(isset($_SESSION['msg'])|| isset($_SESSION['error']))
{
	$msg=$_SESSION['msg'];
	unset($_SESSION['msg']);
	$error=$_SESSION['error'];
	unset($_SESSION['error']);
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
	
	<title>User Details</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">

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
						<h3 class="page-title"><?php echo (!isset($_GET['edit']))? "Add":"Edit"?>  User :<b> <?php echo htmlentities($result['name']); ?></b></h3><?php if($_SESSION['is_admin'] && $_GET['para']==0){?> 
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">User Info<a href='edit-user.php'><img src='./css/add.png' height='20'width='20'align='right' alt="Add User" title='Add User'></a><?php }?></div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<input type="hidden" name="uid" value="<?php echo htmlentities($_GET['edit']);?>">
<input type="hidden" name="action" value="<?php if(isset($_GET['edit']) && $_GET['edit']<>''){ echo "Edit";}else{echo "Add";} ?>">

<div class="form-group">
<label class="col-sm-2 control-label">User Name<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="name" class="form-control" required value="<?php echo htmlentities($result['name']);?>">
</div>
<label class="col-sm-2 control-label">Email<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="email" name="email" class="form-control" required value="<?php echo htmlentities($result['email']);?>">
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Gender<span style="color:red">*</span></label>
<div class="col-sm-4">
<select name="gender" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Male" <?php if ($result['gender']=="Male"){ echo "selected";} ?>>Male</option>
                            <option value="Female" <?php if ($result['gender']=="Female"){ echo "selected";} ?>>Female</option>
							<option value="Not Disclose" <?php if ($result['gender']=="Not Disclose"){ echo "selected";} ?>>Not Disclose</option>

                            </select>
</div>
<label class="col-sm-2 control-label">Designation</label>
<div class="col-sm-4">
<input type="text" name="designation" class="form-control"  value="<?php echo htmlentities($result['designation']);?>">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Password <?php if (!isset($_GET['edit'])){ ?><span style="color:red">*</span><?php }?></label>
<div class="col-sm-4">
<input type="password" name="password" class="form-control"  value="" <?php if (!isset($_GET['edit'])){ ?>required <?php }?> >
</div>

</div>
<div class="form-group">
<label class="col-sm-2 control-label">User Type<span style="color:red">*</span></label>
<div class="col-sm-4">
<select name="user_type" class="form-control" required>
                            <option value="">Select</option>
                            <option value="1" <?php if ($result['user_type']==1){ echo "selected";} ?>>Admin User</option>
                            <option value="0" <?php if ($result['user_type']==0){ echo "selected";} ?>>Noramal User</option>
                            </select>
</div>
<label class="col-sm-2 control-label">Goups</label>
<div class="col-sm-4">
<select name="groups[]" class="form-control"  multiple>
                            <option value="">Select</option>
						<?php foreach ($allGroups as $group){
							
							$selected=(@in_array($group['id'],$Userallgroupsfiltered))? "selected":"";
							
							echo "<option value=".$group['id']." {$selected} >".$group['name']."</option>";
						}?>
                            </select>
</div>
</div>




<div class="form-group">
	<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit"> <?php if(isset($_GET['edit']) && $_GET['edit']<>''){ echo "Update Changes";}else{echo "Add User";} ?></button>
	</div>
</div>

</form>
									</div>
								</div>
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
<?php }?>