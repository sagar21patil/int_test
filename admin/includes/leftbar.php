	<nav class="ts-sidebar">
			<ul class="ts-sidebar-menu">
			
				<li class="ts-label">Main</li>
				<li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			
			<li><a href="userlist.php?para=0"><i class="fa fa-users"></i> Userlist</a>
			</li>
			<li><a href="edit-user.php?edit=<?php echo $_SESSION['userID']; ?>"><i class="fa fa-user"></i> &nbsp;Profile</a>
			</li>
			<li><a href="groupslist.php?para=0"><i class="fa fa-users"></i> &nbsp;Groups</a>
			
			<?php
			if($_SESSION['is_admin'])
			{
			?>
			<li><a href="userlist.php?para=1"><i class="fa fa-user-times"></i> &nbsp;Deleted Users</a>
			</li>
			</li>
			<li><a href="groupslist.php?para=1"><i class="fa fa-users"></i> &nbsp;Deleted Groups</a>
			</li>
			<?php
			}?>
			</ul>
		</nav>

		