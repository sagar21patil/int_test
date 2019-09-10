<?php
require_once("includes/config.php");
require_once('includes/RegistrationClass.php');	
if(strlen($_SESSION['alogin'])==0)
	{	
@header('location:index.php');exit;
}
else{
    $RegistrationObj = new Registration();
	//Get Users, groups count based on active and deleted
	$dashboard_result=$RegistrationObj->dashboard();
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
	
	<title>Admin Dashboard</title>

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
</head>

<body>
<?php include_once('includes/header.php');?>

	<div class="ts-main-content">
<?php include_once('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Dashboard</h2>
						
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
													<div class="stat-panel-number h1 "><?php echo htmlentities($dashboard_result['users_count']);?></div>
													<div class="stat-panel-title text-uppercase">Users</div>
												</div>
											</div>
											<a href="userlist.php?para=0" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-success text-light">
												<div class="stat-panel text-center">
													<div class="stat-panel-number h1 "><?php echo htmlentities($dashboard_result['deleted_users_count']);?></div>
													<div class="stat-panel-title text-uppercase">Deleted Users</div>
												</div>
											</div>
											<a href="userlist.php?para=1" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>

													<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-danger text-light">
												<div class="stat-panel text-center">
													<div class="stat-panel-number h1 "><?php echo htmlentities($dashboard_result['groups_count']);?></div>
													<div class="stat-panel-title text-uppercase">Groups</div>
												</div>
											</div>
											<a href="groupslist.php?para=0" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-info text-light">
												<div class="stat-panel text-center">
													<div class="stat-panel-number h1 "><?php echo htmlentities($dashboard_result['deleted_groups_count']);?></div>
													<div class="stat-panel-title text-uppercase">Deleted groups</div>
												</div>
											</div>
											<a href="groupslist.php?para=1" class="block-anchor panel-footer text-center">Full Detail &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
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
	
	<script>
		
	window.onload = function(){
    
		// Line chart from swirlData for dashReport
		var ctx = document.getElementById("dashReport").getContext("2d");
		window.myLine = new Chart(ctx).Line(swirlData, {
			responsive: true,
			scaleShowVerticalLines: false,
			scaleBeginAtZero : true,
			multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
		}); 
		
		// Pie Chart from doughutData
		var doctx = document.getElementById("chart-area3").getContext("2d");
		window.myDoughnut = new Chart(doctx).Pie(doughnutData, {responsive : true});

		// Dougnut Chart from doughnutData
		var doctx = document.getElementById("chart-area4").getContext("2d");
		window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {responsive : true});

	}
	</script>
</body>
</html>
<?php } ?>