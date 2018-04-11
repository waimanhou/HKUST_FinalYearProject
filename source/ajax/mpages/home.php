<?php include_once('ajax/mpages/header.php');?>
<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<h3>
			<?php
				if(isset($_GET['uid'])){
					$id=$_GET['uid'];
					echo $_GET['frdname'];
				}else{
					$id=$t->id();
					echo $t->fullname();
				}
			?>
		</h3>
	</div>
	<div data-role="content" align="center">
		<table width="100%" height="100%" align="center" id="home_table">
			
				<?php
					if($id!=$t->id()){
				?>
				<tr>
					<td><a href="#" rel=external onclick="loadPage('profile','uid=<?php echo $id;?>');"><img id="home_icon" src="images/icon_profile.png"/><br>Profile</a></td>
					<td><a href="#" rel=external onclick="loadPage('wall','uid=<?php echo $id;?>');"><img id="home_icon" src="images/icon_wall.png"/><br>Wall</a></td>
					<td><a href="#" rel=external onclick="loadPage('friend','uid=<?php echo $id;?>');"><img id="home_icon" src="images/icon_friends.png"/><br>Friends</a></td>
				</tr>
				<tr>
					<td></td>
					<td><a href="#" rel=external onclick="$('#name').val('');loadPage('home');"><img id="home_icon" src="images/icon_back.png"/><br>Back</a></td>
					<td></td>
				</tr>
				<?php }else{ ?>
				<tr>
					<td><a href="#" rel=external onclick="loadPage('wall','t=news');"><img id="home_icon" src="images/icon_news.png"/><br>News</a></td>
					<td><a href="#" rel=external onclick="loadPage('wall','uid=<?php echo $t->id();?>');"><img id="home_icon" src="images/icon_wall.png"/><br>Wall</a></td>
					<td><a href="#" rel=external onclick="loadPage('friend','uid=<?php echo $t->id();?>');"><img id="home_icon" src="images/icon_friends.png"/><br>Friends</a></td>
				</tr>
				<tr>
					<td><a href="#" rel=external><img id="home_icon" src="images/icon_notification.png"/><br>Notifications</a></td>
					<td><a href="#" rel=external><img id="home_icon" src="images/icon_event.png"/><br>Events</a></td>
					<td><a href="#" rel=external onclick="loadPage('checkin');"><img id="home_icon" src="images/icon_checkin.png"/><br>Check-In</a></td>
				</tr>
				<tr>
					<td><a href="#" rel=external onclick="loadPage('profile','uid=<?php echo $t->id();?>');"><img id="home_icon" src="images/icon_profile.png"/><br>Profile</a></td>
					<td><a href="#" rel=external onclick="loadPage('account');"><img id="home_icon" src="images/icon_account.png"/><br>Account</a></td>
					<td><a href="#" rel=external onclick="btnLogout();"><img id="home_icon" src="images/icon_logout.png"/><br>Logout</a></td>
					<td></td>
				</tr>
				<?php } ?>
		</table>
	</div>

<?php include_once('ajax/mpages/footer.php');?>