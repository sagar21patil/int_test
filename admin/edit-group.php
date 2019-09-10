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
	
	
if(isset($_GET['edit']))
	{

		$editid=$_GET['edit'];
		$fields = array('id','name','description');
        $condition = array("id" => $editid);        
        $result=Mysql::getdata("groups",$fields,$condition); 
			
	}


if(isset($_POST['submit']))
  {
	//Add group in to DB
	if($_POST['action']=='Add')
	{
		$addGroup=$RegistrationObj->Insertgroup();
		if($addGroup['success']<>'')
			$_SESSION['success']=$addGroup['success'];
		else
			$_SESSION['error']=$addGroup['error'];
		
		@header('location:edit-group.php?edit='.$addGroup['id']);
		exit;		
	}
	else{
		//Update group in to DB
		$updateGroup=$RegistrationObj->Updategroup();
		if($updateGroup['success']<>'')
			$_SESSION['success']=$updateGroup['success'];
		else
			$_SESSION['error']=$updateGroup['error'];
		
		@header('location:edit-group.php?edit='.$updateGroup['id']);
		exit;
	}

	} 

//Dispaly message on Users listing page
if(isset($_SESSION['success'])|| isset($_SESSION['error']))
{
	//echo "Sagar";die;
	$msg=$_SESSION['success'];
	unset($_SESSION['success']);
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
	
	<title>Group Information</title>

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
						<h3 class="page-title"><?php echo (!isset($_GET['edit']))? "Add":"Edit"?> Group <b>: <?php echo htmlentities($result['name']); ?></b></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Group Info</div>
<?php if($error){?><div class="errorWrap" id="msgshow"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap" id="msgshow"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<input type="hidden" name="gid" value="<?php echo htmlentities($_GET['edit']);?>">
<input type="hidden" name="action" value="<?php if(isset($_GET['edit']) && $_GET['edit']<>''){ echo "Edit";}else{echo "Add";} ?>">

<div class="form-group">
<label class="col-sm-2 control-label">Group Name<span style="color:red">*</span></label>
<div class="col-sm-4">
<input type="text" name="name" class="form-control" required value="<?php echo htmlentities($result['name']);?>">
</div>
</div>
<div class="form-group">

<label class="col-sm-2 control-label">Description<span style="color:red">*</span></label>
<div class="col-sm-4">
<textarea name="description" class="form-control" cols="50" rows="8" required><?php echo htmlentities($result['description']);?></textarea>
</div>
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit"> <?php if(isset($_GET['edit']) && $_GET['edit']<>''){ echo "Update Changes";}else{echo "Add Group";} ?></button>
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