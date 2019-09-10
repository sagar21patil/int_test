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


if(isset($_GET['del_grp'])&& $_GET['del_grp']<>'')
{
	//Before delete check user are belongs to group or not
	$sql="SELECT id FROM users_groups where user_id<>'' AND group_id=".$_GET['del_grp']." AND is_deleted=0";
	$group_users_count=Mysql::raw_sql_count($sql);

	
	if($group_users_count>=1)
	{
		$_SESSION['error']="Group has not deleted. Please remove user(s) from group.";
		@header('location:groupslist.php?para=0');
		exit;		
	}else{
		//If users not exist in group then delete group
		$where=array("id"=>$_GET['del_grp']);
		$result=$RegistrationObj->userAction("groups",$where);
		if($result)
		{
			$_SESSION['msg']="Group has been deleted";
		}else{
			$_SESSION['error']="Group has not deleted. Please contact Administrator.";
		}
		@header('location:groupslist.php?para=0');
		exit;
	}
}


//Dispaly message on Users listing page
if(isset($_SESSION['msg']) || isset($_SESSION['error']))
{
	$msg=$_SESSION['msg'];
	unset($_SESSION['msg']);
	$error=$_SESSION['error'];
	unset($_SESSION['error']);	
}	

	//Load groups depend on active/deleted page
	if(isset($_GET['para']) && ($_GET['para']==0 || $_GET['para']==1))
	{
		$fields = array('id','name','description','modified_date');
        $where = array('is_deleted' => $_GET['para']);        
        $resultArray=Mysql::getdataAll('groups',$fields,$where);	
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

						<h2 class="page-title"><?php echo ($_GET['para']==0)? "Manage":"Deleted"?> Groups</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">List groups <?php if($_SESSION['is_admin'] && $_GET['para']==0){?> <a href='edit-group.php'><img src='./css/add.png' height='20'width='20'align='right' alt="Add User" title='Add Group'></a><?php }?></div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap" id="msgshow"><?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
                                                <th>Name</th>
                                                <th>Description</th> 
												<th width="20%">Modified Date</th> 												
											<?php if($_SESSION['is_admin'] && $_GET['para']==0){?> <th width="10%">Action</th><?php }?>
										</tr>
									</thead>
									
									<tbody>

<?php 


$cnt=1;
if(!empty($resultArray) > 0)
{
foreach($resultArray as $result)
{			

		?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>						
                                            <td><?php echo htmlentities($result['name']);?></td>
                                            <td><?php echo htmlentities($result['description']);?></td>
											 <td><?php echo date("M-d-Y h:i a",strtotime($result['modified_date']));?></td>
<?php
if($_SESSION['is_admin']  && $_GET['para']==0)
{
?>											
<td>
<a href="edit-group.php?edit=<?php echo $result['id'];?>" onclick="return confirm('Do you want to Edit User');" title="Edit Group">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
<a href="groupslist.php?del_grp=<?php echo $result['id'];?>" onclick="return confirm('Do you want to Delete User');" title="Delete Group"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
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
